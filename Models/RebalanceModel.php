<?php
class RebalanceModel {

    const TRIGGER_TYPE_SPEND = 0;
    const TRIGGER_TYPE_DISTRIBUTE = 1;
    const TRIGGER_TYPE_DATE = 2;
    const TRIGGER_TYPE_PERIODICAL = 3;

    const SURPLUS = 0;
    const DEFICIT = 1;
    const SURPLUS_OR_DEFICIT = 2;

    /** ID number of the record (id)
     * @var int $RebalanceId
     */
    public $RebalanceId = NULL;

    /** The category to which this rebalance rule is attached (catid)
     * @var int $CategoryId
     */
    public $CategoryId = NULL;

    /** The trigger rule for which this rule takes effect (type)
     * @var int $TriggerType
     */
    public $TriggerType = 0;

    /** Whether this rule applies to a surplus or a deficit or both (surplusordeficit)
     * @var int $SurplusDeficit
     */
    public $SurplusDeficit = 0;

    /** Which category to send to and which to pull from when the trigger occurs (sendtopullfrom)
     * @var int $SendToPullFrom
     */
    public $SendToPullFrom = NULL;

    /** The date for which this rule applies (dateid)
     * @var DateModel $Date
     */
    public $Date = NULL;

    function toArray(){
        return get_object_vars($this);
    }

}
