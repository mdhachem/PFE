<?php

namespace App\Entity\SearchEntity;

class HomeSearch
{


    private $plan;


    private $place;


    private $category;


    /**
     * Get the value of plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set the value of plan
     *
     * @return  self
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get the value of place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set the value of place
     *
     * @return  self
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}

