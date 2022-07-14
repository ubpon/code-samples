<?php
declare(strict_types=1);

namespace App\External\Interfaces;

use App\Jobs\Job;

interface JobDispatcherInterface
{
    /**
     * Dispatch given job.
     *
     * @param \App\Jobs\Job $job
     *
     * @return void
     */
    public function dispatch(Job $job): void;
}
