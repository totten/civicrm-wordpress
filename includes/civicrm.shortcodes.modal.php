<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 *
 */


// this file must not accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Define CiviCRM_For_WordPress_Shortcodes_Modal Class
 */
class CiviCRM_For_WordPress_Shortcodes_Modal {


  /**
   * Declare our properties
   */

  // init property to store reference to Civi
  public $civi;


  /**
   * Instance constructor
   *
   * @return object $this The object instance
   */
  function __construct() {

    // store reference to Civi object
    $this->civi = civi_wp();

  }


  /**
   * Register hooks to handle the presence of shortcodes in content
   *
   * @return void
   */
  public function register_hooks() {

    // bail if Civi not installed yet
    if ( ! CIVICRM_INSTALLED ) return;

    // adds the CiviCRM button to post and page edit screens
    // use priority 100 to position button to the farright
    add_action( 'media_buttons', array( $this, 'add_form_button' ), 100 );


    // add the javascript and styles to make it all happen
    add_action('load-post.php', array($this, 'add_core_resources'));
    add_action('load-post-new.php', array($this, 'add_core_resources'));
    add_action('load-page.php', array($this, 'add_core_resources'));
    add_action('load-page-new.php', array($this, 'add_core_resources'));

  }


  /**
   * Callback method for 'media_buttons' hook as set in register_hooks()
   *
   * @param string $editor_id Unique editor identifier, e.g. 'content'
   * @return void
   */
  public function add_form_button() {

    // add button to WP selected post types, if allowed
    if ( $this->post_type_has_button() ) {

      $civilogo = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgoKPHN2ZwogICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiCiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYXBlIgogICBpZD0ic3ZnMiIKICAgdmVyc2lvbj0iMS4xIgogICBpbmtzY2FwZTp2ZXJzaW9uPSIwLjkxIHIxMzcyNSIKICAgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIKICAgd2lkdGg9IjEyMy42MDk3IgogICBoZWlnaHQ9IjEyMy42MDk3IgogICB2aWV3Qm94PSIwIDAgMTIzLjYwOTY5IDEyMy42MDk3MSIKICAgc29kaXBvZGk6ZG9jbmFtZT0ic2luZ2xlLWNvbG9yLnN2ZyIKICAgaW5rc2NhcGU6ZXhwb3J0LWZpbGVuYW1lPSIvaG9tZS9hbmRyZXcvUmVjb3Jkcy9jaXZpLWxvZ28tMTZweC5wbmciCiAgIGlua3NjYXBlOmV4cG9ydC14ZHBpPSIxMS42NSIKICAgaW5rc2NhcGU6ZXhwb3J0LXlkcGk9IjExLjY1Ij48bWV0YWRhdGEKICAgICBpZD0ibWV0YWRhdGE4Ij48cmRmOlJERj48Y2M6V29yawogICAgICAgICByZGY6YWJvdXQ9IiI+PGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC9kYzpmb3JtYXQ+PGRjOnR5cGUKICAgICAgICAgICByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPjxkYzp0aXRsZT48L2RjOnRpdGxlPjwvY2M6V29yaz48L3JkZjpSREY+PC9tZXRhZGF0YT48ZGVmcwogICAgIGlkPSJkZWZzNiI+PGNsaXBQYXRoCiAgICAgICBjbGlwUGF0aFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIKICAgICAgIGlkPSJjbGlwUGF0aDE2Ij48cGF0aAogICAgICAgICBkPSJNIDAsNDMyIDg2NCw0MzIgODY0LDAgMCwwIDAsNDMyIFoiCiAgICAgICAgIGlkPSJwYXRoMTgiCiAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGgKICAgICAgIGNsaXBQYXRoVW5pdHM9InVzZXJTcGFjZU9uVXNlIgogICAgICAgaWQ9ImNsaXBQYXRoNDAiPjxwYXRoCiAgICAgICAgIGQ9Im0gNDY4LjM2OSwyMzkuOTYyIDEzLjk0MiwtMTIuMDkzIC00LjM2OCwtNDguODgxIDIuNTIsLTEwLjA3OSAxMC41ODEsNi41NTEgLTEuNTEsMTIuNDI5IDQ5LjcyMSwyNy4yMTQgNC43MDMsOC4wMDUgLTcuNTYsNC4wODkgLTIzLjM0OSwxLjg0NyAtMzAuODIzLDE5LjE1IC0xMy44NTcsMS44NDcgMCwtMTAuMDc5IHoiCiAgICAgICAgIGlkPSJwYXRoNDIiCiAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiIC8+PC9jbGlwUGF0aD48L2RlZnM+PHNvZGlwb2RpOm5hbWVkdmlldwogICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIKICAgICBib3JkZXJjb2xvcj0iIzY2NjY2NiIKICAgICBib3JkZXJvcGFjaXR5PSIxIgogICAgIG9iamVjdHRvbGVyYW5jZT0iMTAiCiAgICAgZ3JpZHRvbGVyYW5jZT0iMTAiCiAgICAgZ3VpZGV0b2xlcmFuY2U9IjEwIgogICAgIGlua3NjYXBlOnBhZ2VvcGFjaXR5PSIwIgogICAgIGlua3NjYXBlOnBhZ2VzaGFkb3c9IjIiCiAgICAgaW5rc2NhcGU6d2luZG93LXdpZHRoPSIxNTUxIgogICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9Ijg0OCIKICAgICBpZD0ibmFtZWR2aWV3NCIKICAgICBzaG93Z3JpZD0iZmFsc2UiCiAgICAgZml0LW1hcmdpbi10b3A9IjAuMjc3NDQiCiAgICAgZml0LW1hcmdpbi1sZWZ0PSIwIgogICAgIGZpdC1tYXJnaW4tcmlnaHQ9IjAiCiAgICAgZml0LW1hcmdpbi1ib3R0b209IjAuMjc3NDQiCiAgICAgaW5rc2NhcGU6em9vbT0iMy44MjM2MTQ0IgogICAgIGlua3NjYXBlOmN4PSI5Mi40OTI4OTEiCiAgICAgaW5rc2NhcGU6Y3k9IjQ2LjU3NzM4NCIKICAgICBpbmtzY2FwZTp3aW5kb3cteD0iNjciCiAgICAgaW5rc2NhcGU6d2luZG93LXk9IjM0IgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjAiCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0iZzEwIiAvPjxnCiAgICAgaWQ9ImcxMCIKICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIgogICAgIGlua3NjYXBlOmxhYmVsPSJjaXZpLWxvZ28tMTIwMzEyIgogICAgIHRyYW5zZm9ybT0ibWF0cml4KDEuMjUsMCwwLC0xLjI1LC01NjAuOTUyLDMyOS43NzA2KSI+PHBhdGgKICAgICAgIHN0eWxlPSJjb2xvcjojMDAwMDAwO2ZvbnQtc3R5bGU6bm9ybWFsO2ZvbnQtdmFyaWFudDpub3JtYWw7Zm9udC13ZWlnaHQ6bm9ybWFsO2ZvbnQtc3RyZXRjaDpub3JtYWw7Zm9udC1zaXplOm1lZGl1bTtsaW5lLWhlaWdodDpub3JtYWw7Zm9udC1mYW1pbHk6c2Fucy1zZXJpZjt0ZXh0LWluZGVudDowO3RleHQtYWxpZ246c3RhcnQ7dGV4dC1kZWNvcmF0aW9uOm5vbmU7dGV4dC1kZWNvcmF0aW9uLWxpbmU6bm9uZTt0ZXh0LWRlY29yYXRpb24tc3R5bGU6c29saWQ7dGV4dC1kZWNvcmF0aW9uLWNvbG9yOiMwMDAwMDA7bGV0dGVyLXNwYWNpbmc6bm9ybWFsO3dvcmQtc3BhY2luZzpub3JtYWw7dGV4dC10cmFuc2Zvcm06bm9uZTtkaXJlY3Rpb246bHRyO2Jsb2NrLXByb2dyZXNzaW9uOnRiO3dyaXRpbmctbW9kZTpsci10YjtiYXNlbGluZS1zaGlmdDpiYXNlbGluZTt0ZXh0LWFuY2hvcjpzdGFydDt3aGl0ZS1zcGFjZTpub3JtYWw7Y2xpcC1ydWxlOm5vbnplcm87ZGlzcGxheTppbmxpbmU7b3ZlcmZsb3c6dmlzaWJsZTt2aXNpYmlsaXR5OnZpc2libGU7b3BhY2l0eToxO2lzb2xhdGlvbjphdXRvO21peC1ibGVuZC1tb2RlOm5vcm1hbDtjb2xvci1pbnRlcnBvbGF0aW9uOnNSR0I7Y29sb3ItaW50ZXJwb2xhdGlvbi1maWx0ZXJzOmxpbmVhclJHQjtzb2xpZC1jb2xvcjojMDAwMDAwO3NvbGlkLW9wYWNpdHk6MTtmaWxsOiM4Mjg3OGM7ZmlsbC1vcGFjaXR5OjE7ZmlsbC1ydWxlOm5vbnplcm87c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjYuNzE4OTk5ODY7c3Ryb2tlLWxpbmVjYXA6YnV0dDtzdHJva2UtbGluZWpvaW46bWl0ZXI7c3Ryb2tlLW1pdGVybGltaXQ6NDtzdHJva2UtZGFzaGFycmF5Om5vbmU7c3Ryb2tlLWRhc2hvZmZzZXQ6MDtzdHJva2Utb3BhY2l0eToxO2NvbG9yLXJlbmRlcmluZzphdXRvO2ltYWdlLXJlbmRlcmluZzphdXRvO3NoYXBlLXJlbmRlcmluZzphdXRvO3RleHQtcmVuZGVyaW5nOmF1dG87ZW5hYmxlLWJhY2tncm91bmQ6YWNjdW11bGF0ZSIKICAgICAgIGQ9Im0gNDc4LjQ3MjY2LDE3MC44NDU3IGMgLTIuMzMxNzQsMC4zNTUzOSAtNC4wNzQ5MSwxLjkzMjE5IC00Ljk5NjEsMy40MDQzIC0xLjg0MjM4LDIuOTQ0MjIgLTEuNzUzOSw2LjAyNTM5IC0xLjc1MzksNi4wMjUzOSBsIC0yLjM3NSw2Ni41MDk3NyBjIC0wLjEwNzU5LDMuMDAxNjMgMC40NDE4OCw1LjQ3MjIzIDEuOTIxODcsNy4zMDg1OSAxLjQ3OTk5LDEuODM2MzYgMy43MTczNSwyLjU0OTA1IDUuNDUzMTMsMi42MDU0NyAzLjQ3MTU0LDAuMTEyODQgNi4wOTE3OSwtMS41MTM2NyA2LjA5MTc5LC0xLjUxMzY3IGwgNTguMjAzMTMsLTMxLjEyODkxIGMgMi42NTExNSwtMS40MTgyMSA0LjUxMTc4LC0zLjE0NjYyIDUuMzU3NDIsLTUuMzQ3NjYgMC44NDU2NCwtMi4yMDEwMyAwLjM1NjM5LC00LjQ4OTY0IC0wLjQ1MzEyLC02LjAyOTI5IC0xLjYxOTA0LC0zLjA3OTMyIC00LjMzMDA4LC00LjU2MjUgLTQuMzMwMDgsLTQuNTYyNSBsIC01NS44MzU5NCwtMzUuMjU1ODYgYyAtMi41Mzk2NiwtMS42MDMzIC00Ljk1MTQ3LC0yLjM3MTAxIC03LjI4MzIsLTIuMDE1NjMgeiBtIDEuMDExNzIsNi42NDI1OCBjIDAuMTMyMzgsLTAuMDIwMiAwLjk2MjI0LC0wLjAzMiAyLjY4MzU5LDEuMDU0NjkgbCA1NS44MzU5NCwzNS4yNTU4NiBjIDAsMCAxLjU0OTk4LDEuMjA3NjIgMS45NzA3LDIuMDA3ODEgMC4yMTAzNiwwLjQwMDA5IDAuMTg0NjcsMC4zNDcwMyAwLjEyODkxLDAuNDkyMTkgLTAuMDU1OCwwLjE0NTE1IC0wLjQ2MTU3LDAuODczMjQgLTIuMjUzOTEsMS44MzIwMyBsIC01OC4yMDMxMywzMS4xMzA4NiBjIDAsMCAtMS44MjMzNywwLjc0OTM2IC0yLjcwNTA3LDAuNzIwNyAtMC40NDA4NiwtMC4wMTQzIC0wLjM1NjYyLC0yLjZlLTQgLTAuNDQxNDEsLTAuMTA1NDcgLTAuMDg0OCwtMC4xMDUyIC0wLjUxMDQyLC0wLjgxNzIgLTAuNDM3NSwtMi44NTE1NiBsIDIuMzc1LC02Ni41MDk3NyBjIDAsMCAwLjI2ODcxLC0xLjk1Mzg5IDAuNzM2MzMsLTIuNzAxMTcgMC4yMzM4MSwtMC4zNzM2MyAwLjE3ODE2LC0wLjMwNTk5IDAuMzEwNTUsLTAuMzI2MTcgeiIKICAgICAgIGlkPSJwYXRoMzQiCiAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIiAvPjxwYXRoCiAgICAgICBzdHlsZT0iY29sb3I6IzAwMDAwMDtmb250LXN0eWxlOm5vcm1hbDtmb250LXZhcmlhbnQ6bm9ybWFsO2ZvbnQtd2VpZ2h0Om5vcm1hbDtmb250LXN0cmV0Y2g6bm9ybWFsO2ZvbnQtc2l6ZTptZWRpdW07bGluZS1oZWlnaHQ6bm9ybWFsO2ZvbnQtZmFtaWx5OnNhbnMtc2VyaWY7dGV4dC1pbmRlbnQ6MDt0ZXh0LWFsaWduOnN0YXJ0O3RleHQtZGVjb3JhdGlvbjpub25lO3RleHQtZGVjb3JhdGlvbi1saW5lOm5vbmU7dGV4dC1kZWNvcmF0aW9uLXN0eWxlOnNvbGlkO3RleHQtZGVjb3JhdGlvbi1jb2xvcjojMDAwMDAwO2xldHRlci1zcGFjaW5nOm5vcm1hbDt3b3JkLXNwYWNpbmc6bm9ybWFsO3RleHQtdHJhbnNmb3JtOm5vbmU7ZGlyZWN0aW9uOmx0cjtibG9jay1wcm9ncmVzc2lvbjp0Yjt3cml0aW5nLW1vZGU6bHItdGI7YmFzZWxpbmUtc2hpZnQ6YmFzZWxpbmU7dGV4dC1hbmNob3I6c3RhcnQ7d2hpdGUtc3BhY2U6bm9ybWFsO2NsaXAtcnVsZTpub256ZXJvO2Rpc3BsYXk6aW5saW5lO292ZXJmbG93OnZpc2libGU7dmlzaWJpbGl0eTp2aXNpYmxlO29wYWNpdHk6MTtpc29sYXRpb246YXV0bzttaXgtYmxlbmQtbW9kZTpub3JtYWw7Y29sb3ItaW50ZXJwb2xhdGlvbjpzUkdCO2NvbG9yLWludGVycG9sYXRpb24tZmlsdGVyczpsaW5lYXJSR0I7c29saWQtY29sb3I6IzAwMDAwMDtzb2xpZC1vcGFjaXR5OjE7ZmlsbDojODI4NzhjO2ZpbGwtb3BhY2l0eToxO2ZpbGwtcnVsZTpub256ZXJvO3N0cm9rZTpub25lO3N0cm9rZS13aWR0aDo2LjcxODk5OTg2O3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1kYXNob2Zmc2V0OjA7c3Ryb2tlLW9wYWNpdHk6MTtjb2xvci1yZW5kZXJpbmc6YXV0bztpbWFnZS1yZW5kZXJpbmc6YXV0bztzaGFwZS1yZW5kZXJpbmc6YXV0bzt0ZXh0LXJlbmRlcmluZzphdXRvO2VuYWJsZS1iYWNrZ3JvdW5kOmFjY3VtdWxhdGUiCiAgICAgICBkPSJtIDQ5MC41MzUxNiwxNjYuNzU5NzcgYyAtMi4zMzk4OSwtMC4yOTYwNyAtNC40NDU2MSwwLjczOTMzIC01LjczODI4LDEuOTAwMzkgLTIuNTg1MzYsMi4zMjIxMSAtMy4zNTM1Miw1LjMxMDU0IC0zLjM1MzUyLDUuMzEwNTQgbCAtMjAuNTk1Nyw2My4xMjExIGMgLTAuOTMxNzUsMi44NTUxNiAtMS4wODQ1Nyw1LjM4MzM1IC0wLjE2OTkzLDcuNTU2NjQgMC45MTQ4NiwyLjE3MzggMi44NjUyNywzLjQ3OTEzIDQuNTE3NTgsNC4wMTU2MiAzLjMwNDYzLDEuMDcyOTkgNi4yNzUzOSwwLjIzODI4IDYuMjc1MzksMC4yMzgyOCBsIDY0Ljc5ODgzLC0xMy43ODkwNiBjIDIuOTM5MTcsLTAuNjI1ODggNS4yMDQzOCwtMS43NjM5OCA2LjYyNjk1LC0zLjY0NDUzIDEuNDIyNTgsLTEuODgwNTUgMS41ODA1NCwtNC4yMTkwNCAxLjIyMjY2LC01LjkxOTkyIC0wLjcxNTc1LC0zLjQwMTc2IC0yLjkxNzk3LC01LjU2NjQxIC0yLjkxNzk3LC01LjU2NjQxIGwgLTQ0LjIwNzAzLC00OS4yOTI5NyBjIC0yLjAwNjE0LC0yLjIzNjg1IC00LjExOTEsLTMuNjMzNjIgLTYuNDU4OTgsLTMuOTI5NjggeiBtIC0wLjg0MTgsNi42NjYwMSBjIDAuMTM5MjQsMC4wMTc2IDAuOTM5OTYsMC4yMzQ4NiAyLjI5ODgzLDEuNzUgbCA0NC4yMDcwMyw0OS4yOTI5NyBjIDAsMCAxLjE1OTc1LDEuNTg4NCAxLjM0Mzc1LDIuNDYyODkgMC4wOTIsMC40MzcyNSAwLjA4NDMsMC4zNjc3NyAtMC4wMDQsMC40ODQzOCAtMC4wODgyLDAuMTE2NiAtMC42Nzg2MywwLjcwMzMzIC0yLjY2Nzk3LDEuMTI2OTUgbCAtNjQuNzk4ODIsMTMuNzg5MDYgYyAwLDAgLTEuOTU4NDIsMC4yMTQ5MiAtMi44MDA3OSwtMC4wNTg2IC0wLjQyMTE4LC0wLjEzNjc2IC0wLjM0ODU3LC0wLjEwNDY2IC0wLjQwMjM0LC0wLjIzMjQyIC0wLjA1MzgsLTAuMTI3NzcgLTAuMjY2NDQsLTAuOTMwMjMgMC4zNjUyNCwtMi44NjUyNCBsIDIwLjU5NTcsLTYzLjEyMTA5IGMgMCwwIDAuNzk0NDMsLTEuODAzMSAxLjQ1NTA4LC0yLjM5NjQ5IDAuMzMwMzIsLTAuMjk2NjkgMC4yNjg5NiwtMC4yNTAwNCAwLjQwODIsLTAuMjMyNDIgeiIKICAgICAgIGlkPSJwYXRoNDYiCiAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIiAvPjwvZz48L3N2Zz4=';
      $url = admin_url( 'admin.php?page=CiviCRM&q=civicrm/shortcode&reset=1' );
      echo '<a href= "' . $url . '" class="button crm-popup medium-popup crm-shortcode-button" data-popup-type="page" style="padding-left: 4px;" title="' . __( 'Add CiviCRM Public Pages', 'civicrm' ) . '"><img src="' . $civilogo . '" height="15" width="15" alt="' . __( 'Add CiviCRM Public Pages', 'civicrm' ) . '" />'. __( 'CiviCRM', 'civicrm' ) .'</a>';

    }

  }


  /**
   * Callback method as set in register_hooks()
   *
   * @return void
   */
  public function add_core_resources() {
    if ($this->civi->initialize()) {
      CRM_Core_Resources::singleton()->addCoreResources();
    }
  }


  /**
   * Does a WordPress post type have the CiviCRM button on it?
   *
   * @return bool $has_button True if the post type has the button, false otherwise
   */
  public function post_type_has_button() {

    // get screen object
    $screen = get_current_screen();

    // bail if no post type (e.g. Ninja Forms)
    if ( ! isset( $screen->post_type ) ) return;

    // get post types that support the editor
    $capable_post_types = $this->get_post_types_with_editor();

    // default allowed to true on all capable post types
    $allowed = ( in_array( $screen->post_type, $capable_post_types ) ) ? true : false;

    // allow plugins to override
    $allowed = apply_filters( 'civicrm_restrict_button_appearance', $allowed, $screen );

    return $allowed;

  }


  /**
   * Get WordPress post types that support the editor
   *
   * @return array $supported_post_types Array of post types that have an editor
   */
  public function get_post_types_with_editor() {

    static $supported_post_types = array();
    if ( !empty( $supported_post_types) ) {
      return $supported_post_types;
    }

    // get only post types with an admin UI
    $args = array(
      'public' => true,
      'show_ui' => true,
    );

    // get post types
    $post_types = get_post_types($args);

    foreach ($post_types AS $post_type) {
      // filter only those which have an editor
      if (post_type_supports($post_type, 'editor')) {
        $supported_post_types[] = $post_type;
      }
    }

    return $supported_post_types;
  }

} // class CiviCRM_For_WordPress_Shortcodes_Modal ends


