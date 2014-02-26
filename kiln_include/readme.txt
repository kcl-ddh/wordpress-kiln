=== Kiln include ===

Configuration options are presented in the WordPress admin interface.

This plugin provides a WordPress `shortcode` called `kiln_include` for
including the resource at the supplied URL. For example:

    [kiln_include url="foo/bar.html"]

The supplied URL is appended to the base URL specified in the options.

If the server the material is fetched from requires HTTP
Authentication, set the `username` and `password` options.
