# wp-personas
A small WordPress Persona marketing framework to extend Hubspot functionality.

  - Create and manage custom marketing Personas inside WordPress
  - Create custom landing pages tailored specifically to your visitors
  - Enhance email campaigns like Mailchimp and Aweber
  - Can be used with other inbound plug-ins to create a Hubspot alternative


From [Wikipedia](https://en.wikipedia.org/w/index.php?title=Persona_%28user_experience%29&gettingStartedReturn=true):

> In user-centered design and marketing, personas are fictional characters created to represent the different user types that might use a site, brand, or product in a similar way.[1] Marketers may use personas together with market segmentation, where the qualitative personas are constructed to be representative of specific segments. The term persona is used widely in online and technology applications as well as in advertising, where other terms such as pen portraits may also be used.

> Personas are useful in considering the goals, desires, and limitations of brand buyers and users in order to help to guide decisions about a service, product or interaction space such as features, interactions, and visual design of a website. Personas may also be used as part of a user-centered design process for designing software and are also considered a part of interaction design (IxD), having been used in industrial design and more recently for online marketing purposes.

> A user persona is a representation of the goals and behavior of a hypothesized group of users. In most cases, personas are synthesized from data collected from interviews with users. They are captured in 1–2-page descriptions that include behavior patterns, goals, skills, attitudes, and the environment, with a few fictional personal details to make the persona a realistic character. For each product, more than one persona is usually created, but one persona should always be the primary focus for the design.

From [Wikipedia](https://en.wikipedia.org/wiki/Inbound_marketing):


> For a related term coined by Seth Godin, see Permission marketing. For the product management sense of Inbound Marketing, see Product management.

> Inbound marketing is promoting a company through blogs, podcasts, video, eBooks, enewsletters, whitepapers, SEO, physical products, social media marketing, and other forms of content marketing which serve to attract customer through the different stages of the purchase funnel.[1][2][3] In contrast, buying attention,[1] cold-calling, direct paper mail, radio, TV advertisements,[2] sales flyers, spam, telemarketing[3] and traditional advertising[4] are considered "outbound marketing". Inbound marketing refers to marketing activities that bring visitors in, rather than marketers having to go out to get prospects' attention. Inbound marketing earns the attention of customers,[1] makes the company easy to be found,[2] and draws customers to the website[4] by producing interesting content.[3]

> David Meerman Scott recommends that marketers "earn their way in" (via publishing helpful information on a blog etc.) in contrast to outbound marketing, where they "buy, beg, or bug their way in" (via paid advertisements, issuing press releases, or paying commissioned sales people, respectively).[5] The term is synonymous with the concept of permission marketing, which is the title of a book by Seth Godin.[3] The inbound marketing term was coined by HubSpot’s Brian Halligan,[2][3][6] in 2005.[7][8] According to HubSpot, inbound marketing is especially effective for small businesses[9][10] that deal with high dollar values, long research cycles and knowledge-based products. In these areas prospects are more likely to get informed and hire someone who demonstrates expertise.[10]

### Version

2.0.3

### Dependencies

WP-Personas depends on [Advanced Custom Fields](https://github.com/elliotcondon/acf) 4.0 by [Elliot Condon](http://www.elliotcondon.com/). A copy of the plugin is included in `'wp-personas/includes/acf/'` and is activated upon plugin initialization. WP Personas defers to your WordPress's install of ACF if activated. 

### Installation & Use

1. Upload 'wp-personas' to the `'/wp-content/plugins/'` directory.
2. Activate WP Personas via the 'Plugins' menu in WordPress.
3. Create a new Persona via the 'WP Personas' dashboard option.
4. Read further to display your Persona's data on landing pages.

### General Use

WP Personas uses two types of data: Persona data and Individual data. 

Persona data describes a particular group or archetype. Persona data lives in the WordPress database and is managed by ACF.

Individual data represents personal information (first name, last name, location, so forth). This information is passed to your landing page via $_GET variables in the URL. The retrieved values are sanitized prior to use.

Persona data and Individual data can both be accessed via shortcodes or PHP calls.

### User API

#### Shortcodes

##### Echo a Persona detail from database:
 * `persona_detail` = Persona detail to be queried (string). Persona details are referred to by their database keys. Persona details 1, 2, and 3 each override, respectively, to the three details in the default Persona field group.
 * `default_persona` (optional) = Default Persona to query if one is not passed in the URL. This can be passed in the form of a Persona's slug (string) or ID (int).
 
```
[mca_d persona_detail (default_persona)]
```

##### Echo an Individual detail from URL:
 * `individual_field` = Key in URL with value to output.
 * `default_output` (optional) = Default output if no value for `individual_field` is given in the URL.

```
[mca_u individual_field (default_output)]
```

Note that both shortcodes output nothing on error. This is by design.

#### URL Paramaters

Installs with basic permalinks use the scheme below:
```
http://domain.com/?p=$pageid&pid=$pid&mcau1=$detail
```

Installs with custom permalinks the scheme below:
```
http://domain.com/your-content/?pid=$pid&mcau1=$detail
```

Individual data may be given and retrieved with any key, but it is wise to avoid keys that may be used elsewhere on your site. When in doubt, start keys with `'mcau'` (or the like) to prevent conflicts. $pid should always be an integer value, as it corresponds to a Persona's post ID.

### Development API

#### Theme / Plugin Integration

##### Echo a Persona detail from database:
 * `$persona_detail` = Persona detail to be queried (string). Persona details are referred to by their database keys. Persona details 1, 2, and 3 each override, respectively, to the three details in the default Persona field group.
 * `$default_persona` (optional) = Default Persona to query if one is not passed in the URL. This can be passed in the form of a Persona's slug (string) or ID (int).

```php
<?php mca_wp_d( $persona_detail [, $default_persona] ); ?>
```

##### Echo an Individual detail from URL:
 * `$individual_field` = Key in URL with value to output.
 * `$default_output` (optional) = Default output if no value for `$individual_field` is given in the URL.

```php
<?php mca_wp_u( $url_detail [, $default_output] ); ?>
```

Note that both functions output nothing on error. This is by design.

#### Register Custom Persona Field Groups

Additional groups of persona details can be registered via `'mca_wp_add_persona_group'`. The code below should be run at plugin/theme init or in your `functions.php`.
```php
<?php add_action( 'mca_wp_add_persona_group', 'mca_wp_register_your_group' ); ?>
```
An example field group is given in `'includes/groups/mca-wp-default-group.php'`.

### Shoutouts

To The team at Adquisitions.

### Todo

- Beef up documentation, include screenshots, add email linking form


Copyright (c) 2015 Brand Ready
