<?php


class Calendar {
    private $events = [];
    private $db;

    public function __construct($date = null) {
        $this->db = new mysqli('localhost', 'root', '', 'medcon_db');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($drid, $nurse_id, $patient, $date, $time, $reason, $hospital) {
        $stmt = $this->db->prepare("INSERT INTO appointment (dr_id, nurse_id, patient_id, date, time, reason, hosp_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("iisssss", $drid, $nurse_id, $patient, $date, $time, $reason, $hospital);
    
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }
    
   

    public function get_events() {
        include "../admin/conn.php";
 $assigned_to = $_SESSION["dr_id"];

        $sql = sprintf("SELECT * FROM appointment WHERE dr_id='%s'",
        $mysqli->real_escape_string($assigned_to));
        $result =$mysqli->query($sql);

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        return $events;
    }

    public function __toString() {
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '<button class="alignright btn btn-light" id="left" id="nextMonth">&gt;</button><button id="prevMonth" class="alignright btn btn-light">&lt</button></div>';
        $html .= '</div>';
        $html .= '<div class="days" data-bs-toggle="modal" data-bs-target="exampleModalCenterTitle">';
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        $num_days = date('t', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . (date('t', strtotime(($this->active_month - 1) . '-' . $this->active_year)) - $i + 1) . '
                </div>
            ';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';
            $events = $this->get_events();
            foreach ($events as $event) {
                $event_date = date('Y-m-d', strtotime($event['date']));
                $current_date = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i));
                if ($event_date == $current_date) {
                    $color = '';
                    $today = date('Y-m-d');
                  if ($event_date < $today) {
                        $color = 'green'; // before today
                    } elseif ($event_date == $today) {
                        $color = 'blue'; // today
                    } else {
                        $color = 'yellow'; // after today
                    }
                    $html .= '<div class="event ' . $color . '"><span class="id" hidden>'.$event["appointment_id"].'</span>';
                    $html .= $event['reason'];
                    $html .= '</div>';
                }
            }
            $html .= '</div>';
        }
        for ($i = 1; $i < 7 - $first_day_of_week; $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
?>