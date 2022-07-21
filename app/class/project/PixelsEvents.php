<?php

class PixelsEvents
{

    private $events = [];
    private $id_pixel;

    public function addEvent($event_name): PixelsEvents
    {
        $events[] = $event_name;
        return $this;
    }

    public function setIdPixel($id_pixel): PixelsEvents
    {
        $this->id_pixel = $id_pixel;
        return $this;
    }

    public function save()
    {
        try {
            $id_pixel = $this->id_pixel;
            $database = new Database();
            if (count($this->events)) {
                foreach ($this->events as $key) {
                    $database->query("INSERT INTO pixels_events (id_pixel, event_name) VALUES (?,?) ");
                    $database->bind(1, $id_pixel);
                    $database->bind(2, $key);
                    $database->execute();
                }
            }

        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function get(): array
    {
        try {
            $database = new Database();
            $database->query("SELECT * FROM pixels_events WHERE is_active = 'Y'");
            $results = $database->resultSet();
            if (count($results) > 0) return $results;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return [];
    }

}