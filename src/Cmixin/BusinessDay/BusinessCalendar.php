<?php

namespace Cmixin\BusinessDay;

use Exception;

class BusinessCalendar extends HolidayObserver
{
    /**
     * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
     *
     * @return \Closure
     */
    public function isBusinessDay()
    {
        $mixin = $this;

        /**
         * Checks the date to see if it is a business day (neither a weekend day nor a holiday).
         *
         * @return bool
         */
        return function ($self = null) use ($mixin) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var \Carbon\Carbon|\Cmixin\BusinessDay $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->isWeekday() && !$self->isHoliday();
        };
    }

    /**
     * Sets the date to the next business day (neither a weekend day nor a holiday).
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function nextBusinessDay($method = 'addDay')
    {
        $mixin = $this;

        /**
         * Sets the date to the next business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self = null) use ($mixin, $method) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            /** @var static $self */
            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            do {
                $self = $self->$method();
            } while (!$self->isBusinessDay());

            return $self;
        };
    }

    /**
     * Sets the date to the current or next business day (neither a weekend day nor a holiday).
     *
     * @param string $method addDay() method by default
     *
     * @return \Closure
     */
    public function currentOrNextBusinessDay($method = 'nextBusinessDay')
    {
        $mixin = $this;

        /**
         * Sets the date to the current or next business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return function ($self = null) use ($mixin, $method) {
            $carbonClass = @get_class() ?: Emulator::getClass(new Exception());

            $self = $carbonClass::getThisOrToday($self, isset($this) && $this !== $mixin ? $this : null);

            return $self->isBusinessDay() ? $self : $self->$method();
        };
    }

    /**
     * Sets the date to the previous business day (neither a weekend day nor a holiday).
     *
     * @return \Closure
     */
    public function previousBusinessDay()
    {
        /**
         * Sets the date to the previous business day (neither a weekend day nor a holiday).
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return $this->nextBusinessDay('subDay');
    }

    /**
     * Sets the date to the current or previous business day.
     *
     * @return \Closure
     */
    public function currentOrPreviousBusinessDay()
    {
        /**
         * Sets the date to the current or previous business day.
         *
         * @return \Carbon\CarbonInterface|\Carbon\Carbon|\Carbon\CarbonImmutable
         */
        return $this->currentOrNextBusinessDay('previousBusinessDay');
    }
}
