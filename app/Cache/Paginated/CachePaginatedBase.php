<?php

namespace App\Cache\Paginated;

use App\Repositories\BaseRepository;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class CachePaginatedBase
{
    protected string $key;
    protected CarbonInterface $ttl;
    protected array $tags;
    protected LengthAwarePaginator|Paginator $data;

    protected abstract function cacheMiss();
    protected abstract function getModelClass(): string;

    function __construct(string $key, CarbonInterface $ttl, array $tags)
    {
        $this->key = $key;
        $this->ttl = $ttl;
        $this->tags = $tags;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function fetch(): Paginator|LengthAwarePaginator
    {
        if(!$this->hasData()){
            $this->data = $this->paginate($this->cacheMiss());
            if($this->data->isNotEmpty()){
                Cache::tags($this->tags)->put($this->key, $this->data, $this->ttl);
            }
        }else{
            $this->data = Cache::tags($this->tags)->get($this->key);
        }

        return $this->data;
    }

    public function hasData(): bool
    {
        return Cache::tags($this->tags)->has($this->key);
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public static function flush(): bool
    {
        $tags = (new static())->getTags();

        return Cache::tags($tags)->flush();
    }

    /**
     * @throws \ReflectionException
     */
    protected function generateKey(): string
    {
        $modelName = (new \ReflectionClass($this->getModelClass()))->getShortName();

        $params = [
            'is_simple' => $this->isSimple,
            'filters' => $this->filters,
            'relationships' => $this->relationships,
        ];

        return generateCacheKey($modelName, $params);
    }

    protected function paginate(BaseRepository $query)
    {
        $limit = $this->filters['limit'] ?? 25;

        $pagination = $this->isSimple ?
            $query->simplePaginate($limit) :
            $query->paginate($limit);

        return $pagination->withQueryString();
    }
}
