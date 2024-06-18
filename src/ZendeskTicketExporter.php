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

        $fp = fopen($filename, 'w', 'b');
        stream_filter_append($fp, 'convert.iconv.UTF-8/UTF-8', STREAM_FILTER_READ);

        fputcsv($fp, [
            'Ticket ID', 'Description', 'Status', 'Priority',
            'Agent ID', 'Agent Name', 'Agent Email',
            'Requester ID', 'Requester Name', 'Requester Email',
            'Group ID', 'Group Name',
            'Organization ID', 'Organization Name',
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
                $ticket['assignee']['name'] ?? '',
                $ticket['assignee']['email'] ?? '',
                $ticket['requester_id'] ?? '',
                $ticket['requester']['name'] ?? '',
                $ticket['requester']['email'] ?? '',
                $ticket['group_id'] ?? '',
                $ticket['group_name'] ?? '',
                $ticket['organization_id'] ?? '',
                $ticket['organization_name'] ?? '',
                $comments,
            ];

            fputcsv($fp, $row);
        }

        fclose($fp);
    }
}