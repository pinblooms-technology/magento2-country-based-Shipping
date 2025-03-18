# Magento 2 - Country Based Shipping Fee

## Overview

**PinBlooms_CountryBasedShipping** is a Magento 2 module that allows store owners to define shipping fees based on customer country, region, or state. This module enables flexible and customizable shipping rules to enhance checkout experience.

## Features
- Configure shipping fees based on country and region.
- Admin panel interface to manage country-based shipping groups.
- Ability to truncate all records from the shipping fee grid.

## Installation
1. Download or clone this repository.
2. Copy the module files to `app/code/PinBlooms/CountryBasedShipping`.
3. Run the following commands in your Magento root directory:
```bash
    php bin/magento module:enable PinBlooms_CountryBasedShipping
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento setup:static-content:deploy -f
    php bin/magento cache:flush
```

## Configuration
- Navigate to **Admin Panel → Country Based Fee → Manage Country Group** to manage shipping fee.
- Define shipping fees per country and region.

## Usage
1. Apply Shipping based on Select country

## Support
For any issues or feature requests, feel free to open a GitHub issue or contact us.

## License
This module is released under the MIT License.
