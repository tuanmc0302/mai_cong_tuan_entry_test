<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * Repo con bắt buộc khai báo model, ví dụ: return User::class;
     */
    abstract public function model(): string;

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function get(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): ?Model
    {
        $record = $this->get($id);

        if (! $record) {
            return null;
        }

        $record->update($attributes);

        return $record;
    }

    public function delete(int $id): bool
    {
        $record = $this->get($id);

        return $record ? (bool) $record->delete() : false;
    }
}