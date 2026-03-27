<?php

return [
    'modules' => [
        'shipping' => [
            'label' => 'Shipping Portal',
            'subtitle' => 'Carrier flows, dispatch, and shipment visibility',
            'permission' => 'portal.shipping.access',
            'items' => [
                [
                    'key' => 'shipping_dashboard',
                    'label' => 'Dashboard',
                    'description' => 'Operational shipping KPIs and queue summaries.',
                    'permission' => 'nav.shipping.dashboard.view',
                ],
                [
                    'key' => 'shipments',
                    'label' => 'Shipments',
                    'description' => 'Create, update, and monitor shipment records.',
                    'permission' => 'nav.shipping.shipments.view',
                ],
                [
                    'key' => 'rate_quotes',
                    'label' => 'Rate Quotes',
                    'description' => 'Compare carrier rates and delivery options.',
                    'permission' => 'nav.shipping.rate_quotes.view',
                ],
                [
                    'key' => 'tracking',
                    'label' => 'Tracking',
                    'description' => 'Track shipment movement and transit exceptions.',
                    'permission' => 'nav.shipping.tracking.view',
                ],
                [
                    'key' => 'returns',
                    'label' => 'Returns',
                    'description' => 'Manage reverse logistics and return labels.',
                    'permission' => 'nav.shipping.returns.view',
                ],
                [
                    'key' => 'customers',
                    'label' => 'Customers',
                    'description' => 'Maintain shipping customer profiles and addresses.',
                    'permission' => 'nav.shipping.customers.view',
                    'action' => [
                        'label' => 'Open Customers',
                        'route' => 'customers.index',
                    ],
                ],
            ],
        ],
        'accounting' => [
            'label' => 'Accounting Portal',
            'subtitle' => 'Billing, collections, and financial controls',
            'permission' => 'portal.accounting.access',
            'items' => [
                [
                    'key' => 'accounting_dashboard',
                    'label' => 'Dashboard',
                    'description' => 'Revenue, receivables, and payables snapshot.',
                    'permission' => 'nav.accounting.dashboard.view',
                ],
                [
                    'key' => 'invoices',
                    'label' => 'Invoices',
                    'description' => 'Issue and manage customer invoices (AR).',
                    'permission' => 'nav.accounting.invoices.view',
                ],
                [
                    'key' => 'bills',
                    'label' => 'Bills',
                    'description' => 'Track supplier bills and outgoing obligations (AP).',
                    'permission' => 'nav.accounting.bills.view',
                ],
                [
                    'key' => 'payments',
                    'label' => 'Payments',
                    'description' => 'Record payments, settlements, and receipts.',
                    'permission' => 'nav.accounting.payments.view',
                ],
                [
                    'key' => 'reconciliation',
                    'label' => 'Reconciliation',
                    'description' => 'Reconcile accounts and bank movements.',
                    'permission' => 'nav.accounting.reconciliation.view',
                ],
                [
                    'key' => 'financial_reports',
                    'label' => 'Financial Reports',
                    'description' => 'Profit & loss, balance sheet, and cash-flow views.',
                    'permission' => 'nav.accounting.reports.view',
                ],
            ],
        ],
        'inventory' => [
            'label' => 'Inventory Portal',
            'subtitle' => 'Stock control, procurement, and warehouse visibility',
            'permission' => 'portal.inventory.access',
            'items' => [
                [
                    'key' => 'inventory_dashboard',
                    'label' => 'Dashboard',
                    'description' => 'Stock health, velocity, and restock indicators.',
                    'permission' => 'nav.inventory.dashboard.view',
                ],
                [
                    'key' => 'stock_overview',
                    'label' => 'Stock Overview',
                    'description' => 'Current stock levels across warehouses.',
                    'permission' => 'nav.inventory.stock_overview.view',
                ],
                [
                    'key' => 'products_skus',
                    'label' => 'Products & SKUs',
                    'description' => 'Product catalog, variants, and SKU details.',
                    'permission' => 'nav.inventory.products.view',
                ],
                [
                    'key' => 'purchase_orders',
                    'label' => 'Purchase Orders',
                    'description' => 'Create and track incoming purchase orders.',
                    'permission' => 'nav.inventory.purchase_orders.view',
                ],
                [
                    'key' => 'adjustments',
                    'label' => 'Adjustments',
                    'description' => 'Manual stock corrections and approvals.',
                    'permission' => 'nav.inventory.adjustments.view',
                ],
                [
                    'key' => 'cycle_counts',
                    'label' => 'Cycle Counts',
                    'description' => 'Scheduled counts and variance handling.',
                    'permission' => 'nav.inventory.cycle_counts.view',
                ],
            ],
        ],
    ],
];
