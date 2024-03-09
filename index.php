<?php

namespace NamePlugin;

class NameApi {
    protected $apiUrl;

    public function __construct($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    public function listVacancies($userId, $vacancyId = 0) {
        $page = 0;
        $vacancies = [];
        $found = null;

        do {
            $response = $this->apiSend($this->buildQuery($userId, $page));
            $data = json_decode($response);

            if ($data === false || !isset($data->objects)) {
                break;
            }

            foreach ($data->objects as $vacancy) {
                if ($vacancyId > 0 && $vacancy->id == $vacancyId) {
                    $found = $vacancy;
                    break 2; // Exit both loop and "do while"
                }
                $vacancies[] = $vacancy;
            }

            $page++;
        } while ($data->more);

        return $found ?: $vacancies;
    }

    protected function apiSend($url) {
        // Implementation of API request (e.g., cURL or file_get_contents)
        return '';
    }

    protected function buildQuery($userId, $page) {
        $params = http_build_query([
            'status' => 'all',
            'id_user' => $userId,
            'with_new_response' => 0,
            'order_field' => 'date',
            'order_direction' => 'desc',
            'page' => $page,
            'count' => 100,
        ]);

        return $this->apiUrl . '/hr/vacancies/?' . $params;
    }
}
