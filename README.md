# PropelMedia Integration for Magento 2

Include `LoganStellway_PropelMedia` in your project to provide server-to-server tracking with PropelMedia ad campaigns. 

## Installation

**Install via Composer**
```
composer require loganstellway/module-propel-media
```

## Configuration

Configuration can be found under:
```
Stores > Configuration > Propel Media > Propel Media Tracking
```

**Options**

- General
  - **Enable**
    - Enable/disable the module
- Campaigns
  - **Token Parameters**
    - Names of valid *open parameters* that will contain token values from your ad campaigns. 
- Reporting
  - **Base URL**
    - Base URL used for reporting to PropelMedia. 
      - Defaults to `https://tracking.propelmedia.com/`, but left editable in case the URL ever changes. 
  - **Parameters**
    - Query parameters and values used for sending conversion data to PropelMedia. 
      - Special Values:
        - **{token}**
          - Replaced with token captured by token parameter
        - **{value}**
          - On order submissions, this is replaced with the order *grand total*

## Implementation Details

This module mainly utilizes event observers to accomplish the desired functionality.

**Observed Events**

- **controller_front_send_response_before**
  - An event observer is attached here to look for query parameters that contain token values from the configured parameters. 
- **checkout_submit_all_after** / **paypal_express_place_order_success**
  - An event observer is attached here to report conversions to PropelMedia servers for users that are visiting from an ad campaign. 
