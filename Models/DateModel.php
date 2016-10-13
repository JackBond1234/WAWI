<?php
class DateModel {

    /** ID number of the record (id)
     * @var int $DateId
     */
    public $DateId = NULL;

    /** The start date or ultimate date (date)
     * @var int $Date
     */
    public $Date = 0;

    /** The type of the unit days/weeks/months/years (unittype)
     * @var int $UnitType
     */
    public $UnitType = 0;

    /** The number of d/w/m/y to skip over between each event (nthunit)
     * @var int $NthUnit
     */
    public $NthUnit = 0;

    /** Whether to handle days like the 31st of February by ignoring it or by defaulting to the last day of the month (impossibledayofmonth)
     * @var int $ImpossibleDayOfMonthBehavior
     */
    public $ImpossibleDayOfMonthBehavior = 0;

    /** Whether to repeat monthly by "the third monday in the month" or by "the 17th of the month" (usedayofweek)
     * @var bool $UseDayOfWeek
     */
    public $UseDayOfWeek = false;

    /** Override the date selected (except as an effective date) and make the event occur at the end of each month (useendofmonth)
     * @var bool $UseEndOfMonth
     */
    public $UseEndOfMonth = false;

    function toArray(){
        return get_object_vars($this);
    }

}
