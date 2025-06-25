<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_holidays($year = null) {
        if (!$year) {
            $year = date('Y');
        }
        
        // Get holidays from Google Calendar
        $google_holidays = $this->get_google_calendar_holidays($year);
        
        // Get school-specific holidays from database
        $school_holidays = $this->get_school_holidays($year);
        
        // Combine and sort all holidays
        $all_holidays = array_merge($google_holidays, $school_holidays);
        usort($all_holidays, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        return $all_holidays;
    }
    
    private function get_google_calendar_holidays($year) {
        $holidays = array();
        
        try {
            // Google Calendar iCal feed for Indian holidays
            $ical_url = "https://calendar.google.com/calendar/ical/en.indian%23holiday%40group.v.calendar.google.com/public/basic.ics";
            
            $ical_content = file_get_contents($ical_url);
            
            if ($ical_content) {
                $events = $this->parse_ical($ical_content, $year);
                foreach ($events as $event) {
                    $holidays[] = array(
                        'name' => $event['summary'],
                        'date' => $event['date'],
                        'description' => 'National Holiday',
                        'source' => 'google'
                    );
                }
            }
        } catch (Exception $e) {
            // Fallback to static holidays if API fails
            $holidays = $this->get_fallback_holidays($year);
        }
        
        return $holidays;
    }
    
    private function parse_ical($ical_content, $year) {
        $events = array();
        $lines = explode("\n", $ical_content);
        
        $current_event = null;
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (strpos($line, 'BEGIN:VEVENT') === 0) {
                $current_event = array();
            } elseif (strpos($line, 'END:VEVENT') === 0) {
                if ($current_event && isset($current_event['date']) && isset($current_event['summary'])) {
                    $event_year = date('Y', strtotime($current_event['date']));
                    if ($event_year == $year) {
                        $events[] = $current_event;
                    }
                }
                $current_event = null;
            } elseif ($current_event !== null) {
                if (strpos($line, 'DTSTART') === 0) {
                    $date_str = substr($line, strpos($line, ':') + 1);
                    $current_event['date'] = date('Y-m-d', strtotime($date_str));
                } elseif (strpos($line, 'SUMMARY') === 0) {
                    $current_event['summary'] = substr($line, strpos($line, ':') + 1);
                }
            }
        }
        
        return $events;
    }
    
    private function get_school_holidays($year) {
        $this->db->select('holiday_name, holiday_date, description');
        $this->db->from('holidays');
        $this->db->where('YEAR(holiday_date)', $year);
        $this->db->where('is_active', 1);
        $this->db->order_by('holiday_date', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $holidays = array();
        foreach ($result as $row) {
            $holidays[] = array(
                'name' => $row['holiday_name'],
                'date' => $row['holiday_date'],
                'description' => $row['description'],
                'source' => 'school'
            );
        }
        
        return $holidays;
    }
    
    private function get_fallback_holidays($year) {
        // Fallback static holidays if Google Calendar fails
        $holidays = array(
            array('name' => 'Republic Day', 'date' => $year . '-01-26', 'description' => 'National Holiday', 'source' => 'fallback'),
            array('name' => 'Independence Day', 'date' => $year . '-08-15', 'description' => 'National Holiday', 'source' => 'fallback'),
            array('name' => 'Gandhi Jayanti', 'date' => $year . '-10-02', 'description' => 'National Holiday', 'source' => 'fallback'),
            array('name' => 'Christmas', 'date' => $year . '-12-25', 'description' => 'Christian Festival', 'source' => 'fallback'),
        );
        
        return $holidays;
    }
    
    public function add_school_holiday($data) {
        return $this->db->insert('holidays', $data);
    }
    
    public function get_upcoming_holidays($limit = 5) {
        $today = date('Y-m-d');
        
        $this->db->select('holiday_name, holiday_date, description');
        $this->db->from('holidays');
        $this->db->where('holiday_date >=', $today);
        $this->db->where('is_active', 1);
        $this->db->order_by('holiday_date', 'ASC');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }
} 