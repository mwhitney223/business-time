<?php

namespace BusinessTime\Traits;

use BusinessTime\Calculator;
use BusinessTime\IntervalComposer;
use Cmixin\BusinessTime;

trait AddAndSubtract
{
    protected $maxIteration = BusinessTime::MAX_ITERATION;

    /**
     * Set the maximum of loop turns to run before throwing an exception where trying to add
     * or subtract open/closed time.
     *
     * @return void|\Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function setMaxIteration()
    {
        if (func_num_args()) {
            $this->maxIteration = func_get_arg(0);

            return;
        }

        $mixin = $this;

        /**
         * Set the maximum of loop turns to run before throwing an exception where trying to add
         * or subtract open/closed time.
         */
        return function (int $maximum) use ($mixin) {
            $mixin->setMaxIteration($maximum);
        };
    }

    /**
     * Get the maximum of loop turns to run before throwing an exception where trying to add
     * or subtract open/closed time.
     *
     * @return int|\Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function getMaxIteration()
    {
        if (func_num_args()) {
            return $this->maxIteration;
        }

        $mixin = $this;

        /**
         * Get the maximum of loop turns to run before throwing an exception where trying to add
         * or subtract open/closed time.
         */
        return function () use ($mixin): int {
            return $mixin->getMaxIteration(true);
        };
    }

    /**
     * Shift current time with a given interval taking into account only open time
     * (if $open is true) or only closed time (if $open is false).
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function applyBusinessInterval()
    {
        $holidaysAreClosedOption = static::HOLIDAYS_ARE_CLOSED;

        /**
         * Shift current time with a given interval taking into account only open time
         * (if $open is true) or only closed time (if $open is false).
         *
         * @param bool                          $inverted subtract the interval if set to true.
         * @param bool                          $open     take only open time into account if true,
         *                                                only closed time else.
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (bool $inverted, bool $open, $interval = null, $unit = null, int $options = 0)
            use ($holidaysAreClosedOption) {
            $calculator = new Calculator(
                isset($this) ? $this : static::now(),
                (new IntervalComposer(static::class, $inverted, $interval, $unit))->getInterval(),
                $open,
                $options & $holidaysAreClosedOption
            );

            return $calculator->calculate($this->getMaxIteration());
        };
    }

    /**
     * Add the given interval taking into account only open time
     * (if $open is true) or only closed time (if $open is false).
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addBusinessInterval()
    {
        /**
         * Add the given interval taking into account only open time
         * (if $open is true) or only closed time (if $open is false).
         *
         * @param bool                          $open     take only open time into account if true,
         *                                                only closed time else.
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (bool $open, $interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->applyBusinessInterval(false, $open, $interval, $unit, $options);
        };
    }

    /**
     * Add the given interval taking into account only open time
     * (if $open is true) or only closed time (if $open is false).
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subBusinessInterval()
    {
        /**
         * Add the given interval taking into account only open time
         * (if $open is true) or only closed time (if $open is false).
         *
         * @param bool                          $open     take only open time into account if true,
         *                                                only closed time else.
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (bool $open, $interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->applyBusinessInterval(true, $open, $interval, $unit, $options);
        };
    }

    /**
     * Add the given interval taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addOpenTime()
    {
        /**
         * Add the given interval taking into account only open time.
         *
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function ($interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->addBusinessInterval(true, $interval, $unit, $options);
        };
    }

    /**
     * Subtract the given interval taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subOpenTime()
    {
        /**
         * Subtract the given interval taking into account only open time.
         *
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function ($interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->subBusinessInterval(true, $interval, $unit, $options);
        };
    }

    /**
     * Add the given interval taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addClosedTime()
    {
        /**
         * Add the given interval taking into account only closed time.
         *
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function ($interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->addBusinessInterval(false, $interval, $unit, $options);
        };
    }

    /**
     * Subtract the given interval taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subClosedTime()
    {
        /**
         * Subtract the given interval taking into account only closed time.
         *
         * @param int|\DateInterval|string|null $interval period default interval or number of the given $unit.
         * @param string|null                   $unit     if specified, $interval must be an integer.
         * @param int                           $options  options (as bytes-union) such as
         *                                                BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function ($interval = null, $unit = null, int $options = 0) {
            return (isset($this) ? $this : static::now())
                ->subBusinessInterval(false, $interval, $unit, $options);
        };
    }

    /**
     * Add the given number of minutes taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addOpenMinutes()
    {
        $unit = static::MINUTE_UNIT;

        /**
         * Add the given number of minutes taking into account only open time.
         *
         * @param int $numberOfMinutes number of minutes (in open hours).
         * @param int $options         options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfMinutes, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->addOpenTime($numberOfMinutes, $unit, $options);
        };
    }

    /**
     * Subtract the given number of minutes taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subOpenMinutes()
    {
        $unit = static::MINUTE_UNIT;

        /**
         * Subtract the given number of minutes taking into account only open time.
         *
         * @param int $numberOfMinutes number of minutes (in open hours).
         * @param int $options         options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfMinutes, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->subOpenTime($numberOfMinutes, $unit, $options);
        };
    }

    /**
     * Add the given number of minutes taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addClosedMinutes()
    {
        $unit = static::MINUTE_UNIT;

        /**
         * Add the given number of minutes taking into account only closed time.
         *
         * @param int $numberOfMinutes number of minutes (in open hours).
         * @param int $options         options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfMinutes, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->addClosedTime($numberOfMinutes, $unit, $options);
        };
    }

    /**
     * Subtract the given number of minutes taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subClosedMinutes()
    {
        $unit = static::MINUTE_UNIT;

        /**
         * Subtract the given number of minutes taking into account only closed time.
         *
         * @param int $numberOfMinutes number of minutes (in open hours).
         * @param int $options         options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfMinutes, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->subClosedTime($numberOfMinutes, $unit, $options);
        };
    }

    /**
     * Add the given number of hours taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addOpenHours()
    {
        $unit = static::HOUR_UNIT;

        /**
         * Add the given number of hours taking into account only open time.
         *
         * @param int $numberOfHours number of open hours.
         * @param int $options       options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfHours, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->addOpenTime($numberOfHours, $unit, $options);
        };
    }

    /**
     * Subtract the given number of hours taking into account only open time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subOpenHours()
    {
        $unit = static::HOUR_UNIT;

        /**
         * Subtract the given number of hours taking into account only open time.
         *
         * @param int $numberOfHours number of open hours.
         * @param int $options       options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfHours, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->subOpenTime($numberOfHours, $unit, $options);
        };
    }

    /**
     * Add the given number of hours taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function addClosedHours()
    {
        $unit = static::HOUR_UNIT;

        /**
         * Add the given number of hours taking into account only closed time.
         *
         * @param int $numberOfHours number of open hours.
         * @param int $options       options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfHours, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->addClosedTime($numberOfHours, $unit, $options);
        };
    }

    /**
     * Subtract the given number of hours taking into account only closed time.
     *
     * @return \Closure<\Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface>
     */
    public function subClosedHours()
    {
        $unit = static::HOUR_UNIT;

        /**
         * Subtract the given number of hours taking into account only closed time.
         *
         * @param int $numberOfHours number of open hours.
         * @param int $options       options (as bytes-union) such as BusinessTime::HOLIDAYS_ARE_CLOSED
         *
         * @return \Carbon\Carbon|\Carbon\CarbonImmutable|\Carbon\CarbonInterface
         */
        return function (int $numberOfHours, int $options = 0) use ($unit) {
            return (isset($this) ? $this : static::now())
                ->subClosedTime($numberOfHours, $unit, $options);
        };
    }
}
