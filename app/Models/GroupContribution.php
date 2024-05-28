<?php
namespace App\Models;

class GroupReport
{
    public $lastName;
    public $firstName;
    public $usi;
    public $award;

    public function __construct($lastName, $firstName, $usi, $award)
    {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->usi = $usi;
        $this->award = $award;
    }
}
