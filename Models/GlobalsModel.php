<?php
class GlobalsModel {

    /** The total amount of money in the account (totalbalance)
     * @var float $TotalBalance
     */
    public $TotalBalance = 0.00;


    /** The expected income amount by a pay period (expectedperiodincome)
     * @var float $ExpectedPeriodIncome
     */
    public $ExpectedPeriodIncome = 0.00;

    /** The frequency of each paycheck (payfreqdateid)
     * @var DateModel $Date
     */
    public $Date = null;

    function toArray(){
        return get_object_vars($this);
    }
}
