<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo html_escape($quote['quote_number']); ?> Quotation</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #132026; font-size: 12px; }
        .header { margin-bottom: 20px; }
        .badge { display:inline-block; padding: 5px 10px; background: #0f766e; color: #fff; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #d7d7d7; padding: 8px; text-align: left; }
        th { background: #f2f6f5; }
        .totals td { font-weight: bold; }
        .notice { background: #fff4da; border-left: 4px solid #c68a2b; padding: 10px 12px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="badge">Liquid CP Quotation</span>
        <h1><?php echo html_escape($quote['quote_number']); ?></h1>
        <p><strong><?php echo html_escape($quote['quote_title']); ?></strong></p>
        <p><?php echo html_escape($quote['client_name']); ?> | <?php echo html_escape($quote['client_email']); ?> | <?php echo html_escape($quote['company_name']); ?></p>
        <div class="notice"><?php echo html_escape($prototype_disclaimer); ?></div>
    </div>

    <table>
        <tbody>
            <tr><th>Total Due</th><td><?php echo number_format($quote['response']['total_due'], 2); ?> ZMW</td></tr>
            <tr><th>Build Cost</th><td><?php echo number_format($quote['response']['build_cost'], 2); ?> ZMW</td></tr>
            <tr><th>Net Build Cost</th><td><?php echo number_format($quote['response']['net_build_cost'], 2); ?> ZMW</td></tr>
            <tr><th>ROI (Months)</th><td><?php echo $quote['response']['roi_months'] === null ? 'N/A' : number_format($quote['response']['roi_months'], 2); ?></td></tr>
            <tr><th>ROI (Years)</th><td><?php echo $quote['response']['roi_years'] === null ? 'N/A' : number_format($quote['response']['roi_years'], 3); ?></td></tr>
            <tr><th>Ruleset</th><td><?php echo html_escape($quote['response']['rule_set_version']); ?></td></tr>
            <tr><th>Engine</th><td><?php echo html_escape($quote['response']['engine_version']); ?></td></tr>
        </tbody>
    </table>

    <?php foreach ($quote['response']['line_items'] as $category => $items) { ?>
        <h3><?php echo ucfirst($category); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo html_escape($item['label']); ?></td>
                        <td><?php echo html_escape($item['quantity']); ?></td>
                        <td><?php echo html_escape($item['unit']); ?></td>
                        <td><?php echo number_format($item['unit_rate'], 2); ?></td>
                        <td><?php echo number_format($item['amount'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</body>
</html>
