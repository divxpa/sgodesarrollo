<?php
/**
 * PGCalendar
 *
 * @package
 * @author Facundo Sola
 * @copyright Copyright (c) 2013
 * @access public
 */
include_once("protected/classes/php-calendar-1.0/php-calendar/classes/calendar.php");
setlocale(LC_ALL, "es_ES");

class PGCalendar extends TCompositeControl{
	private $_calendar = null;
	private $_panel = null;

	public function __construct(){
        $this->setViewState("currentMonth", date('n'));
        $this->setViewState("currentYear", date('Y'));
        $this->ConstruirCalendar();
	}

    public function onInit($param){
        parent::onInit($param);
    }

    public function onLoad($param){
        parent::onLoad($param);
    }

    public function ConstruirCalendar(){    
        $this->_calendar = Calendar::factory($this->getViewState("currentMonth", date('n')), $this->getViewState("currentYear", date('Y')));
        $this->_calendar->standard('today')->standard('prev-next');
        
        $events = $this->getViewState("events", array());

        foreach ($events as $evt) {
            $event =  $this->_calendar->event()
            ->condition('month', $evt["month"])
            ->condition('day', $evt["day"])
            ->title($evt["title"])
            ->output($evt["output"]);
            $this->_calendar->attach($event);
        }

        $this->_panel = new TActivePanel();
        $this->_panel->getActiveControl()->EnableUpdate = true;
        $this->_panel->EnableViewState = true;
        $this->_panel->setID("PGPanel");
  
        $table = $this->GenerateHtmlCalendar();
        $this->_panel->getControls()->clear();
        $this->_panel->getControls()->add($table);
        $this->getControls()->add($this->_panel);

        $this->setViewState("calendar", $this->_calendar);
        $this->setViewState("panel", $this->_panel);
    }

	public function GenerateHtmlCalendar(){
        $table = new TTable();
        $table->setCssClass("calendar");
        $table->setID("PGTable");

        $tableHeaderRow = new TTableHeaderRow();
        $tableHeaderRow->setCssClass("navigation");
        
        $tableHeaderCell = new TTableHeaderCell();
        $tableHeaderCell->setCssClass("prev-month");
        $button = new TActiveButton();
        $button->Text = $this->_calendar->prev_month();
        $button->onCallBack[] = array($this, "CalendarPreviousDayOnClick");
        $button->setID("PGPreviousDayButton");
        $tableHeaderCell->getControls()->add($button);
        $tableHeaderRow->Cells->add($tableHeaderCell);
        
        $tableHeaderCell = new TTableHeaderCell();
        $tableHeaderCell->setCssClass("current-month");
        $tableHeaderCell->ColumnSpan=5;
        $label = new TActiveLabel();
        $label->Text = $this->_calendar->month()." ".$this->_calendar->year;
        $tableHeaderCell->getControls()->add($label);
        $button = new TActiveButton();
        $button->Text = "Hoy";
        $button->onCallBack[] = array($this, "CalendarTodayOnClick");
        $button->setID("PGTodayButton");
        $tableHeaderCell->getControls()->add($button);
        $tableHeaderRow->Cells->add($tableHeaderCell);

        $tableHeaderCell = new TTableHeaderCell();
        $tableHeaderCell->setCssClass("next-month");
        $button = new TActiveButton();
        $button->Text = $this->_calendar->next_month();
        $button->onCallBack[] = array($this, "CalendarNextDayOnClick");
        $button->setID("PGNextDayButton");
        $tableHeaderCell->getControls()->add($button);
        $tableHeaderRow->Cells->add($tableHeaderCell);

        $table->Rows->add($tableHeaderRow);

        $tableHeaderRow = new TTableHeaderRow();
        $tableHeaderRow->setCssClass("weekdays");

        foreach ($this->_calendar->days() as $day){
            $tableHeaderCell = new TTableHeaderCell();
            $tableHeaderCell->Text = utf8_encode($day);
            $tableHeaderRow->Cells->add($tableHeaderCell);
        }

        $table->Rows->add($tableHeaderRow);

        foreach ($this->_calendar->weeks() as $week){
            $tableRow = new TTableRow();

            foreach ($week as $day){
                list($number, $current, $data) = $day;

                $classes = array();
                $output  = '';
                     
                if (is_array($data))
                {
                    $classes = $data['classes'];
                    $title   = $data['title'];
                    $output  = empty($data['output']) ? '' : '<ul class="output"><li>'.implode('</li><li>', $data['output']).'</li></ul>';

                    $tableCell = new TTableCell();
                    $tableCell->setCssClass("day ".implode(' ', $classes));
                    $tableCell->Text = "<span class=\"date\" title=\"".implode(' / ', $title)."\">".$number."</span>
                                        <div class=\"day-content\">".$output."</div>";
                    $tableRow->Cells->add($tableCell);
                }

            }

            $table->Rows->add($tableRow);
        }

        return $table;
	}

	public function addEvent($title, $output, $month, $day){
        $events = $this->getViewState("events", array());
        
        $event = array(
                    "month" => $month,
                    "day" => $day,
                    "title" => $title,
                    "output" => $output
                );

        $events[] = $event;
        $this->setViewState("events", $events);
	}

    public function CalendarPreviousDayOnClick($sender, $param){
        $currentMonth = $this->getViewState("currentMonth", date('n'));
        $currentYear = $this->getViewState("currentYear", date('Y'));
        $date  = mktime(0, 0, 0, $currentMonth - 1, 1, $currentYear);
        $currentMonth = date('n', $date);
        $currentYear  = date('Y', $date);
        $this->setViewState("currentMonth", $currentMonth);
        $this->setViewState("currentYear", $currentYear);
        $this->ShowCalendar($param);
        /*$this->ConstruirCalendar();
        $this->_panel->render($param->getNewWriter());*/
    }

    public function CalendarNextDayOnClick($sender, $param){
        $currentMonth = $this->getViewState("currentMonth", date('n'));
        $currentYear = $this->getViewState("currentYear", date('Y'));
        $date  = mktime(0, 0, 0, $currentMonth + 1, 1, $currentYear);
        $currentMonth = date('n', $date);
        $currentYear  = date('Y', $date);
        $this->setViewState("currentMonth", $currentMonth);
        $this->setViewState("currentYear", $currentYear);
        $this->ShowCalendar($param);
        /*$this->ConstruirCalendar();
        $this->_panel->render($param->getNewWriter());*/
    }

    public function CalendarTodayOnClick($sender, $param){
        $date  = mktime(0, 0, 0, date('n'), 1, date('Y'));
        $currentMonth = date('n', $date);
        $currentYear  = date('Y', $date);
        $this->setViewState("currentMonth", $currentMonth);
        $this->setViewState("currentYear", $currentYear);
        $this->ShowCalendar($param);
    }

    public function ShowCalendar($param){
        $this->_calendar = Calendar::factory($this->getViewState("currentMonth", date('n')), $this->getViewState("currentYear", date('Y')));
        $this->_calendar->standard('today')->standard('prev-next');

        $events = $this->getViewState("events", array());

        foreach ($events as $evt) {
            $event =  $this->_calendar->event()
            ->condition('month', $evt["month"])
            ->condition('day', $evt["day"])
            ->title($evt["title"])
            ->output($evt["output"]);
            $this->_calendar->attach($event);
        }

        $table = $this->GenerateHtmlCalendar();
        $this->_panel->getControls()->clear();
        $this->_panel->getControls()->add($table);
        $this->getControls()->add($this->_panel);

        $this->_panel->render($param->getNewWriter());
    }

    public function RefreshCalendar(){    
        $calendar = $this->getViewState("calendar", null);
        $panel = $this->getViewState("panel", null);

        if(is_null($calendar)){
            $this->_calendar = Calendar::factory($this->getViewState("currentMonth", date('n')), $this->getViewState("currentYear", date('Y')));
            $this->_calendar->standard('today')->standard('prev-next');
        }
        else{
            $this->_calendar = $calendar;
        }
        
        $events = $this->getViewState("events", array());

        foreach ($events as $evt) {
            $event =  $this->_calendar->event()
            ->condition('month', $evt["month"])
            ->condition('day', $evt["day"])
            ->title($evt["title"])
            ->output($evt["output"]);
            $this->_calendar->attach($event);
        }

        if(is_null($panel)){
            $this->_panel = new TActivePanel();
            $this->_panel->getActiveControl()->EnableUpdate = true;
            $this->_panel->EnableViewState = true;
            $this->_panel->setID("PGPanel");
        }
        else{
            $this->_panel = $panel;
        }

        $table = $this->GenerateHtmlCalendar();
        $this->_panel->getControls()->clear();
        $this->_panel->getControls()->add($table);
        $this->getControls()->add($this->_panel);

        $this->setViewState("calendar", $this->_calendar);
        $this->setViewState("panel", $this->_panel);
    }

}
