Staff-List-Plugin
=================

Staff Listing plugin for WordPress using Custom Post Types

This is my first crack at writing an actual plugin (vs. placing custom code into the theme's functions.php file).
It was adapted from this plugin by Adam Tootle: http://wordpress.org/extend/plugins/staff-directory/ It used custom
database tables to store it's data and I thought it was over-complicating things a bit. I wanted to use the power of
custom post types to create the staff listing.

It gives the user the ability to add a custom template for displaying the staff member information using tags such as
[name], [email_link] and so on. The user can also add custom css to style the output. I've included a drag-and-drop
method to sort the staff members and set the default display order.

I am very open to receiving feedback and constructive criticism on this project so I can learn and write better, more
efficent WordPress plugins in the future.

## Current Features
- Staff name as title
- Preset staff profile fields
- Groups custom taxonomy
- Single "Staff Loop Template" with template tags like `[staff-name]`
- Staff Loop Template editor
- Custom CSS editor - can write to theme or print css to page

## Future Features
- Staff name broken into first, last, suffix
  - Combine these fields and save as the post title/slug
  - I have the code for converting CPT title into first/last/suffix
  - My idea for conversion is to have a button on one of the admin screens with appropriate warnings about backing up, may not work for every name, etc, etc
- Ability to add custom staff profile fields - needs to automatically create template tags for use in the staff loop templates
- Ability to create multiple Staff Loop templates
- Plugin should offer a few preset template layout options w/CSS
  - These templates should have some sort of "live" preview of what the template would look like
  - Selecting a template would populate the staff loop template box with the appropriate template tags - user could edit
  - Selecting a template would populate the custom CSS box with the appropriate CSS - user could edit
- Figure out best practice for including a single-staff-member.php page template with the plugin.
  - Think it's this: https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template#Example_with_Custom_Post_Type
  - Should we need to take the bio field and also save it as $post->post_content?
  - Or, also along those lines, what if, on post save, use the staff loop template and generate the output and save it as $post->post_content ? That way, even if the end user doesn't know how to make a custom template, they still get something.
