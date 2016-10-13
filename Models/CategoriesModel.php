<?php
class CategoriesModel {

  /** ID number of the record (catid)
  * @var int $CategoryId
  */
  public $CategoryId = NULL;

  /** Current balance held in the category (balance)
  * @var float $Balance
  */
  public $Balance = 0.00;

  /** ID number of the category with a higher priority than this one (higherpriority)
  * @var int $HigherPriorityCategory
  */
  public $HigherPriorityCategory = NULL;

  /** ID number of the category with a lower priority than this one (lowerpriority)
  * @var int $LowerPriorityCategory
  */
  public $LowerPriorityCategory = NULL;

  /** Accrual Behavior Type (accrueby)
  * @var int $AccrueBy
  */
  public $AccrueBy = 0;

  /** Amount or Percentage to accrue to this category when allocating (accrualamount)
  * @var int $AccrualAmount
  */
  public $AccrualAmount = 0.00;

  /** The name of the category (name)
  * @var string $Name
  */
  public $Name = "";

  /** The maximum amount able to be accrued in this category (cap)
   * @var float $Cap
   */
  public $Cap = 0.00;

  /** The color of the category for organizational purposes (color)
   * @var string $Color
   */
  public $Color = "#FFFFFF";

  /** The target date for when saving should be complete (targetdate)
   * @var DateModel $TargetDate
   */
  public $TargetDate = null;

  /** The calculated target date that applied to the previous distribution (lasttarget)
   * @var int $LastTarget
   */
  public $LastTarget = 0;

  /** The amount accrued towards the last target since the period began (accruedinperiod)
   * @var float $AccruedInPeriod
   */
  public $AccruedInPeriod = 0.00;

  public function toArray(){
    return get_object_vars($this);
  }

}
