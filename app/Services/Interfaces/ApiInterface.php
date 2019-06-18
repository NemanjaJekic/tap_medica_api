<?php


namespace App\Services;



use Illuminate\Support\Collection;

interface ApiInterface
{
    /**
     * @return mixed
     */
    public function getClient();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return mixed
     */
    public function mapData();


    /**
     * @param string $property
     * @param string $value
     * @param string $operator
     * @return Collection
     */
    public function filterBy(string $property, string $value, string $operator);


    /**
     * @return mixed
     */
    public function store();
}