<?php
class WebhookService {
    private $webhooks = [];

    public function registerWebhook($url, $events) {
        $hookId = uniqid('webhook_');
        $this->webhooks[$hookId] = [
            'url' => $url,
            'events' => $events,
            'active' => true,
            'created_at' => time()
        ];
        return $hookId;
    }

    public function triggerWebhook($event, $data) {
        $results = [];
        foreach ($this->webhooks as $id => $hook) {
            if ($hook['active'] && in_array($event, $hook['events'])) {
                $results[] = $this->sendRequest($hook['url'], $event, $data);
            }
        }
        return $results;
    }

    private function sendRequest($url, $event, $data) {
        $payload = json_encode(['event' => $event, 'data' => $data, 'timestamp' => time()]);
        return [
            'url' => $url,
            'event' => $event,
            'status' => 'sent',
            'payload_size' => strlen($payload)
        ];
    }

    public function disableWebhook($hookId) {
        if (isset($this->webhooks[$hookId])) {
            $this->webhooks[$hookId]['active'] = false;
            return true;
        }
        return false;
    }
}
?>
