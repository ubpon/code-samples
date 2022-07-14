<?php
declare(strict_types=1);

namespace App\External\Libraries;

use App\External\Interfaces\JobDispatcherInterface;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\Dispatcher as IlluminateJobDispatcher;

class JobDispatcher implements JobDispatcherInterface
{
    /**
     * @var \Illuminate\Contracts\Bus\Dispatcher
     */
    private $dispatcher;

    /**
     * JobDispatcher constructor.
     *
     * @param \Illuminate\Contracts\Bus\Dispatcher $dispatcher
     */
    public function __construct(IlluminateJobDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch given job.
     *
     * @param \App\Jobs\Job $job
     *
     * @return void
     */
    public function dispatch(Job $job): void
    {
        $this->dispatcher->dispatch($job);
    }
}
