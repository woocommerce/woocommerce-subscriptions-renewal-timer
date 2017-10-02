# WooCommerce Subscriptions Renewal Timer

Log the beginning and end of subscription renewal events. Helps track how long the renewal process is taking.

To do this, the plugin logs important data around renewal events to WooCommerce log file prefixed with `'wcs-renewal-log-'`.

To view the log file:

1. Go to **WooCommerce > System Status > Logs** (i.e. `/wp-admin/admin.php?page=wc-status&tab=logs`)
1. Select the log file with the `'wcs-renewal'` prefix

### Installation

1. Upload the plugin's files to the `/wp-content/plugins/` directory of your WordPress site
1. Activate the plugin through the **Plugins** menu in WordPress

### Requirements

In order to use the extension, you will need:

* WooCommerce Subscriptions v2.1 or newer (you could use it on older versions, but there is no need)
* WooCommerce v2.4 or newer, the version required by Subscriptions v2.1

### Further Reading

The [Subscriptions Renewal Process Guide](https://docs.woocommerce.com/document/subscriptions/renewal-process/#section-5) provides additional background on the what activties occur during the renewal process.

#### License

This plugin is released under [GNU General Public License v3.0](http://www.gnu.org/licenses/gpl-3.0.html).

---

<p align="center">
<img src="https://cloud.githubusercontent.com/assets/235523/11986380/bb6a0958-a983-11e5-8e9b-b9781d37c64a.png" width="160">
</p>
