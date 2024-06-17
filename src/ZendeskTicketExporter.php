<?php

class ZendeskTicketExporter
{
    private $api;

    public function __construct(ZendeskAPI $api)
    {
        $this->api = $api;
    }

    public function exportTicketsToCsv($filename)
    {
        $tickets = $this->api->getTickets();

        $fp = fopen($filename, 'w', 'b'); // Додано 'b' для бінарного режиму
        stream_filter_append($fp, 'convert.iconv.UTF-8/UTF-8', STREAM_FILTER_READ);

        fputcsv($fp, [
            'Ticket ID', 'Description', 'Status', 'Priority',
            'Agent ID', 'Agent Name', 'Agent Email',
            'Contact ID', 'Contact Name', 'Requester Email', // Змінено тут
            'Group ID', 'Group Name',
            'Company ID', 'Company Name',
            'Comments',
        ]);

        foreach ($tickets as $ticket) {
            $comments = '';
            if (isset($ticket['comments']) && is_array($ticket['comments'])) {
                $comments = implode("\n", array_column($ticket['comments'], 'body'));
            }
            $row = [
                $ticket['id'],
                $ticket['description'] ?? '',
                $ticket['status'] ?? '',
                $ticket['priority'] ?? '',
                $ticket['assignee_id'] ?? '',
                $ticket['assignee_name'] ?? '',
                $ticket['assignee_email'] ?? '',
                $ticket['requester_id'] ?? '',
                $ticket['requester']['name'] ?? '', // Припустимо, що ім'я запитувача знаходиться за шляхом $ticket['requester']['name']
                $ticket['requester']['email'] ?? '', // Припустимо, що електронна пошта запитувача знаходиться за шляхом $ticket['requester']['email']
                $ticket['group_id'] ?? '',
                $ticket['group_name'] ?? '',
                $ticket['company_id'] ?? '',
                $ticket['company_name'] ?? '',
                $comments,
            ];

            fputcsv($fp, $row);
        }

        fclose($fp);
    }
}