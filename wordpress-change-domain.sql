SET @old_url = 'http://www.oldurl';
SET @new_url = 'http://www.newurl';

UPDATE wp_options SET option_value = replace(option_value, @old_url, @new_url) WHERE option_name = 'home' OR option_name = 'siteurl';

UPDATE wp_posts SET guid = replace(guid, @old_url, @new_url);

UPDATE wp_posts SET post_content = replace(post_content, @old_url, @new_url);

UPDATE wp_postmeta SET meta_value = replace(meta_value, @old_url, @new_url);
