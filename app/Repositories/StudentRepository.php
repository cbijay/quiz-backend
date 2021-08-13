<?php
namespace App\Repositories;


use App\Repositories\BaseRepository;

class AnswerRepository
{

public function getPayments()
{
    $payments = $this->payment->with('students')->get();

    return $payments;
}