# ads-banners
The wordpress plugin that allows you to put banners on your wordpress website. Implemented via custom post type. Supports Google Ads. Basically can be any kind of content supported by Gutenberg Editor ( any text/images/lists/links ). Includes custom gutenberg block ( that can be only set on 'ad' post type ) for Google Ads banner. Supports custom banner size, default + custom ads locations, linking directly to posts( currently only of 'post' post type ), linking directly to posts categories.

# Theme Integration
There are a few default locations for ads: 
<code>
	article-sidebar-ads,
	article-content-ads,
	article-popup-ads,
	home-popup-ads,
	home-content-ads,
</code>

Which means that you only need to call a hook to show the ad in needed location in your theme, i.e&nbsp;
<code>
  do_action( "article-sidebar-ads" ); // for sidebar ads&nbsp; 
  do_action( "home-popup-ads" ); // for popup / overlay ads on home page&nbsp;
</code>

Also custom locations are supported, you only need to go to plugin settings which is available by clicking 'Ads Settings' menu item on admin dashboard and speficy custom locations split by comma: location1,location2,location3; After that these custom locations will appear on ad settings sidebar(the one on right side).

Custom locations theme integration:
Simply use the hooks in the same way as we did with default locations, but use the next hook name:
<br/><code>
   "print-custom-ad-" . $location
</code><br/>

where location is the name of your location ( whitespaces are forbidden ).

# Usage
On admin dashboard click on 'Ads Banners' menu item, then create ad post, just like a regular one and put the content you want to see in ad. After that simply select ad location on right sidebar( which is called block settings ) and save post.

# Usage: Google Ads
Firstly see #usage, but instead of your content add Google Ads block, make it active by clicking on it and set google ads client & slot fields on right sidebar ( this data is provided by google ).

# Screenshots : Settings
<img src="https://i.imgur.com/lMLzirh.png" alt="Screenshot: Settings" />

# Screenshots : Ad
<img src="https://i.imgur.com/e41ZBxF.png" alt="Screenshot: Ad" />


# Screenshots : Google Ads banner
<img src="https://i.imgur.com/LdjjIAp.png" alt="Screenshot: Ad" />
