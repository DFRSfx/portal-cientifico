<?php

namespace App\Observers;

use App\Models\Output;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class OutputObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Output "created" event.
     */
    public function created(Output $output): void
    {
        //
    }

    /**
     * Handle the Output "updated" event.
     */
    public function updated(Output $output): void
    {
        //
    }

    /**
     * Handle the Output "deleted" event.
     */
    public function deleted(Output $output): void
    {
        $output->polymorphic()->delete();
    }

    /**
     * Handle the Output "restored" event.
     */
    public function restored(Output $output): void
    {
        //
    }

    /**
     * Handle the Output "force deleted" event.
     */
    public function forceDeleted(Output $output): void
    {
        //
    }
}
