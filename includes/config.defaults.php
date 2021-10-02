<?php

/*
 * Looking Glass - An easy to deploy Looking Glass
 * Copyright (C) 2014-2021 Guillaume Mazoyer <guillaume@mazoyer.eu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

/*
 * Please don't edit this file!
 * Make changes to the configuration array in config.php.
 */

function set_defaults_for_routers(&$parsed_config) {
  $router_defaults = array(
    'timeout' => 30,
    'disable_ipv6' => false,
    'disable_ipv4' => false,
    'bgp_detail' => false
  );

  // Loads defaults when key does not exist
  foreach ($parsed_config['routers'] as &$router) {
    foreach ($router_defaults as $key => $value) {
      if (!array_key_exists($key, $router)) {
        $router[$key] = $value;
      }
    }
  }
}

$config = array(

  // Release configuration
  'release' => array(
    'version' => '2.1.0',
    'codename' => 'Established',
    'repository' => 'https://github.com/gmazoyer/looking-glass'
  ),

  // Frontpage configuration
  'frontpage' => array(
    // CSS to use
    'css' => 'css/style.css',
    // Extra HTML header elements
    'additional_html_header' => null,
    // Title
    'title' => 'Looking Glass',
    // Image (null for no image)
    'image' => null,
    // Image width and heoght (0 to ignore)
    'image_width' => 0,
    'image_height' => 0,
    // Link for the title/image
    'header_link' => null,
    // Peering Policy file (null for no peering policy)
    'peering_policy_file' => null,
    // Disclaimer (null for no disclaimer)
    'disclaimer' => 'Disclaimer example',
    // Display the title
    'show_title' => true,
    // Show visitor IP address
    'show_visitor_ip' => true,
    // Frontpage order you can use: routers, commands, parameter, buttons
    'order' => array('routers', 'commands', 'parameter', 'buttons'),
    // Number of routers to show on frontpage
    'router_count' => 1,
    // Number of commands to show on frontpage (0 scales dynamically)
    'command_count' => 0
  ),

  // Contact (both null for no contact)
  'contact' => array(
    // Name of the contact
    'name' => 'Example Support',
    // Email of the contact
    'mail' => 'support@example.com'
  ),

  // Output control
  'output' => array(
    // Show or hide command in output
    'show_command' => true
  ),

  // Filters
  'filters' => array(
    // Lines (based on regexp) not to show in the output
    'output' => array(),
    // AS path regexps to disallow
    'aspath_regexp' => array(
      '.',
      '.*',
      '.[,]*',
      '.[0-9,0-9]*',
      '.[0-9,0-9]+'
    )
  ),

// Google reCaptcha
  'recaptcha' => array(
    // Disabled by default
    'enabled' => true,
    'url' => 'https://www.google.com/recaptcha/api/siteverify',
    'apikey' => '6Ld0H3scAAAAADe30eo-XwYzlatydFDPjETWX-Uc',
    'secret' => '6Ld0H3scAAAAAB_TtDhqZoOHx_7YXheggwIWhz01'
  ),

  // Logs
  'logs' => array(
    // Logs file where commands will be written
    'file' => '/var/log/looking-glass.log',
    // Format for each logged command (%D is for the time, %R is for the
    // requester IP address, %H is for the host and %C is for the command)
    'format' => '[%D] [client: %R] %H > %C',
    // Logs authentication debug details to the logs file
    'auth_debug' => false
  ),

  // Misc
  'misc' => array(
    // Allow private ASN
    'allow_private_asn' => false,
    // Allow RFC1918 IPv4 and FD/FC IPv6 as parameters
    'allow_private_ip' => true,
    // Allow reserved IPv4 addresses (0.0.0.0/8, 169.254.0.0/16,
    // 192.0.2.0/24 and 224.0.0.0/4)
    'allow_reserved_ip' => true,
    // Allowed prefix length for route lookup
    'minimum_prefix_length' => array(
      'ipv6' => 0,
      'ipv4' => 0
    ),
    // Extract user "real" IP from the HTTP_X_FORWARDED_FOR header
    //  as this header can be spoofed by the user, it's not recommended to enable this option.
    'enable_http_x_forwarded_for' => false,

  ),

  // Tools used for some processing
  'tools' => array(
    // Options to be used when pinging from a UNIX host (case of BIRD, Quagga,
    // and others)
    'ping_options' => '-A -c 10',
    // Source option to use when pinging
    'ping_source_option' => '-I',
    // Traceroute tool to be used
    'traceroute4' => 'traceroute -4',
    'traceroute6' => 'traceroute -6',
    // Options to be used when tracerouting from a UNIX host (case of BIRD,
    // Quagga, and others)
    'traceroute_options' => '-q1 -w2 -m15',
    // Source option to use when tracerouting
    'traceroute_source_option' => ''
  ),

  // Documentation (must be HTML)
  'doc' => array(
    // Documentation for the 'show route' query
    'bgp' => array(
      'command' => 'show route IP_ADDRESS',
      'description' => 'Muestra las mejores rutas del destino solicitado.',
      'parameter' => 'EL parámetro debe ser un destino válido. Destino, se refiere a una dirección o subred IPv4/IPv6. Las máscaras también son aceptadas como una dirección IPv4/IPv6.<br />Direcciones RFC1918, IPv6 comenzando con FD o FC, e IPv4 con rangos reservados (0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 y 224.0.0.0/4) pueden ser rechazados.<br />Tome en cuenta que algunos routers siempre necesitan recibir una máscara cuando se está buscando una dirección IPv6.<br /><br />Ejemplos de argumentos válidos:<br /><ul><li>8.8.8.8</li><li>8.8.4.0/24</li><li>2001:db8:1337::42</li><li>2001:db8::/32</li>'
    ),
    // Documentation for the 'as-path-regex' query
    'as-path-regex' => array(
      'command' => 'show route as-path-regex AS_PATH_REGEX',
      'description' => 'Muestra las rutas que coinciden con la expresión regular de un Sistema Autónomo dado.',
      'parameter' => 'El parámetro debe ser una expresión regular de ruta de un Sistema Autónomo válido y no debe contener ningún carácter: ", (La entrada se citará automáticamente si es necesario).<br />Tome en cuenta que estas expresiones pueden cambiar según el enrutador y su software.<br />OpenBGPD no admite expresiones regulares, pero buscará el número de Sistema Autónomo enviado en cualquier lugar de la ruta del Sistema Autónomo.<br /><br />Ejemplos de argumentos válidos :<ul><li><strong>Juniper</strong> - ^AS1 AS2 .*$</li><li><strong>Cisco</strong> - ^AS1_AS2_</li><li><strong>BIRD</strong> - AS1 AS2 AS3 &hellip; ASZ</li><li><strong>OpenBGPD</strong> - AS1</li></ul><br />Puede encontrar ayuda en la siguiente documentación:<br /><ul><li><a href="http://www.juniper.net/techpubs/en_US/junos13.3/topics/reference/command-summary/show-route-aspath-regex.html" title="Juniper Documentation">Juniper Documentation</a></li><li><a href="http://www.cisco.com/c/en/us/support/docs/ip/border-gateway-protocol-bgp/26634-bgp-toc.html#asregexp" title="Cisco Documentation">Cisco Documentation</a></li><li><a href="http://bird.network.cz/?get_doc&f=bird-5.html" title="BIRD Documentation">BIRD Documentation</a> (search for bgpmask)</li></ul>'
    ),
    // Documentation for the 'as' query
    'as' => array(
      'command' => 'show route ^AS',
      'description' => 'Muestra las rutas recibidas de un número de un Sistema Autónomo vecino dado.',
      'parameter' => 'El parámetro debe ser un número de sistema autónomo válido de 16 o 32 bits.<br /> Precaución: Los Números de Sistema Autónomo de 32 bits no son manejados por enrutadores antiguos o software de enrutadores antiguos .<br />A menos que se especifique, el Número de Sistema Autónomo privado se considerará no válido.<br /><br />Ejemplos de argumentos válidos:<br /><ul><li>15169</li><li>29467</li></ul>'
    ),
    // Documentation for the 'ping' query
    'ping' => array(
      'command' => 'ping IP_ADDRESS|HOSTNAME',
      'description' => 'Envía pings al destino solicitado',
      'parameter' => 'El parámetro debe ser una dirección IPv4/IPv (sin máscara) o un nombre de host.<br />Se pueden rechazar las direcciones RFC1918, IPv6 que comiencen con FD o FC, y rangos reservados de IPv4 (0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 y 224.0.0.0/4).<br /><br />Ejemplos de argumentos válidos:<br /><ul><li>8.8.8.8</li><li>2001:db8:1337::42</li><li>example.com</li></ul>'
    ),
    // Documentation for the 'traceroute' query
    'traceroute' => array(
      'command' => 'traceroute IP_ADDRESS|HOSTNAME',
      'description' => 'Muestra la ruta a un destino determinado.',
      'parameter' => 'El parámetro debe ser una dirección IPv4/IPv (sin máscara) o un nombre de host.<br />Se pueden rechazar las direcciones RFC1918, IPv6 que comiencen con FD o FC, y rangos reservados de IPv4 (0.0.0.0/8, 169.254.0.0/16, 192.0.2.0/24 y 224.0.0.0/4).<br /><br />Ejemplos de argumentos válidos:<br /><ul><li>8.8.8.8</li><li>2001:db8:1337::42</li><li>example.com</li></ul>'
    )
  ),

  // Routers
  'routers' => array()

);

// End of config.defaults.php
