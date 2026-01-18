<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Booking\BookingService;
use App\Http\Requests\CancelBookingRequest;
use App\Http\Resources\Api\BookingResource;
use App\Http\Controllers\Traits\ApiResponses;
use App\Http\Requests\Api\Booking\ApprovedRefundRequest;
use App\Http\Requests\Api\Booking\CancelBookingRequest as BookingCancelBookingRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\RescheduleBookingRequest;
use App\Http\Requests\Api\Booking\StoreBookingRequest;
use App\Http\Requests\Api\Booking\UpdateBookingRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Api\Booking\CheckAvailabiltyRequest;
use App\Http\Requests\Api\Booking\DenyRefundRequest;
use App\Http\Requests\Api\Booking\RequestRefundRequest as BookingRequestRefundRequest;
use App\Http\Requests\Api\Booking\RescheduleBookingRequest as BookingRescheduleBookingRequest;
use App\Http\Requests\RequestRefundRequest;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BookingController extends Controller
{
    use ApiResponses;

    protected string $resource = BookingResource::class;

    public function __construct(protected BookingRepositoryInterface $bookingRepository, private BookingService $bookingService) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $bookings = $this->bookingRepository
            ->with(['user', 'service'])
            ->when(
                !in_array($user->role, ['Admin', 'Staff']),
                fn($query) => $query->filterByUserId($user->id)
            )
            ->filterByKeyword($request->input('keyword'))
            ->filterByStatus($request->input('status'))
            ->filterByDateRange($request->input('start_date'), $request->input('end_date'))
            ->filterByTimeRange($request->input('start_time'), $request->input('end_time'))
            ->orderBy('booking_date', 'asc')
            ->paginate();

        return $this->success([
            'data' => BookingResource::collection($bookings),
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $payload = $request->validated();
        $user = $request->user();

        try {
            if (!$this->bookingService->isServiceBookable($payload['service_id'])) {
                return $this->failed(
                    message: __('This service is not available for booking.'),
                    code: Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            if (!$this->bookingService->isAvailable(
                serviceId: $payload['service_id'],
                date: $payload['booking_date'],
                startTime: $payload['start_time'],
                endTime: $payload['end_time']
            )) {
                return $this->failed(
                    message: __('This time slot is not available.'),
                    code: Response::HTTP_CONFLICT
                );
            }

            $booking = $this->bookingService->createBooking([
                ...$payload,
                'user_id' => $user->id,
            ]);

            $booking->load(['user', 'service']);

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking created successfully.'),
                code: Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->failed(
                message: __('Failed to create booking.'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show(int $id)
    {
        try {
            $user = Auth::user();

            $booking = $this->bookingRepository
                ->with(['user', 'service'])
                ->find($id);

            if (
                !in_array($user->role, ['Admin', 'Staff']) &&
                $booking->user_id !== $user->id
            ) {
                return $this->failed(
                    message: __('Unauthorized access to this booking.'),
                    code: Response::HTTP_FORBIDDEN
                );
            }

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking retrieved successfully.')
            );
        } catch (ModelNotFoundException) {
            return $this->failed(
                message: __('Booking not found.'),
                code: Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->failed(
                message: __('Failed to retrieve booking.'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(UpdateBookingRequest $request, int $id)
    {
        $payload = $request->validated();
        $user = $request->user();

        try {
            $booking = $this->bookingRepository->find($id);

            if (
                !in_array($user->role, ['Admin', 'Staff']) &&
                $booking->user_id !== $user->id
            ) {
                return $this->failed(
                    message: __('Unauthorized to update this booking.'),
                    code: Response::HTTP_FORBIDDEN
                );
            }

            if (!$this->bookingService->isAvailable(
                serviceId: $payload['service_id'],
                date: $payload['booking_date'],
                startTime: $payload['start_time'],
                endTime: $payload['end_time'],
                excludeBookingId: $booking->id
            )) {
                return $this->failed(
                    message: __('This time slot is not available.'),
                    code: Response::HTTP_CONFLICT
                );
            }

            $booking->update($payload);
            $booking->load(['user', 'service']);

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking updated successfully.')
            );
        } catch (ModelNotFoundException) {
            return $this->failed(
                message: __('Booking not found.'),
                code: Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->failed(
                message: __('Failed to update booking.'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function approve(int $id)
    {
        try {
            $booking = $this->bookingService->approveBooking($id);

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking approved successfully.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            report($e);

            return $this->failed(
                __('Failed to approve booking.'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function cancel(int $id, BookingCancelBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->cancelBooking(
                $id,
                $request->validated('reason')
            );

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking cancelled successfully.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function requestRefund(int $id, BookingRequestRefundRequest $request)
    {
        try {
            $validated = $request->validated();

            $booking = $this->bookingService->requestRefund(
                $id,
                $validated['reason'] ?? null
            );

            return $this->successWithResource(
                resource: $booking,
                message: __('Refund requested successfully.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function approveRefund(int $id, ApprovedRefundRequest $request)
    {
        try {
            $booking = $this->bookingService->approveRefund(
                $id,
                $request->validated('refund_amount')
            );

            return $this->successWithResource(
                resource: $booking,
                message: __('Refund approved successfully.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function denyRefund(int $id, DenyRefundRequest $request)
    {
        try {
            $booking = $this->bookingService->denyRefund(
                $id,
                $request->validated('reason')
            );

            return $this->successWithResource(
                resource: $booking,
                message: __('Refund denied.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function markAsNoShow(int $id)
    {
        try {
            $booking = $this->bookingService->markAsNoShow($id);

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking marked as no-show.')
            );
        } catch (\Throwable $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function reschedule(int $id, BookingRescheduleBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->rescheduleBooking(
                $id,
                $request->validated('new_date'),
                $request->validated('new_start_time'),
                $request->validated('new_end_time')
            );

            return $this->successWithResource(
                resource: $booking,
                message: __('Booking rescheduled successfully.')
            );
        } catch (UnprocessableEntityHttpException $e) {
            return $this->failed($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function checkAvailability(CheckAvailabiltyRequest $request)
    {
        $available = $this->bookingService->isAvailable(
            $request->service_id,
            $request->date,
            $request->start_time,
            $request->end_time,
            $request->exclude_booking_id
        );

        return $this->success([
            'available' => $available,
            'message' => $available
                ? __('Time slot is available.')
                : __('Time slot is not available.'),
        ]);
    }
}
