# Magento 2 — Sales Order Export

## Aim of the module

This Magento 2 module purpose is to provide an out of the box wrapper that can handle your order export. It provides:
  * A `export:order` Magento command that can be run at any moment.
  * A `sales_order_export` CRON task that runs every hour.
  * A model that save which order way exported and which should still be.
  * A log file `<magento>/var/log/export_order.log` that journalizes every action done.
  * Comments on order that was exported visible on the back office.

## What you still have to do

You will have to create your own module that will rewrite the `\Spydemon\SalesOrderExport\Exporter\Order::exportOrders` method in order to implement what you really need to do for the export. Don't forget to call the `\Spydemon\SalesOrderExport\Exporter\Order::markOrderAsExported` method in it for flagging orders that was exported.

## Warnings

The purpose of this module is more to help developers to save time instead of providing ready to use tools.
Don't expect to be able to use it out of the box without a minimal integration on your side.

## Compatibility

This module was tested on the Magento versions that follows.

| Version | State |
| ------- | ----- |
| 2.3.5-p1 | Works |

## How to install it

Using Composer for installing this module is the best way:

```
composer require spydemon/m2-sales_order_export
```

## Help appreciated

If you like this module and find a bug or an enhancement, don't hesitate to fill an issue, or even better: a pull request. 😀
