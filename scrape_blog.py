#!/usr/bin/env python3
"""
Scrape blog posts from mynzhome.com/blog
"""
import requests
from bs4 import BeautifulSoup
import json
import time

def get_post_ids_and_images():
    """Get all blog post IDs and their featured images from pagination"""
    posts_data = {}
    for page in range(1, 10):  # Check up to 10 pages
        url = f"https://mynzhome.com/blog?page_group1={page}"
        response = requests.get(url)
        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Find all blog post links that wrap tiles
        links = soup.find_all('a', href=lambda x: x and '/blog_post/' in x)
        if not links:
            break
            
        for link in links:
            # Get post ID from link
            post_id = link['href'].split('/blog_post/')[-1]
            # Find the tile wrapper inside this link
            tile = link.find('div', class_='blog_tile_wrapper')
            if tile:
                # Get featured image
                img_wrapper = tile.find('div', class_='blog_tile_img_wrapper')
                if img_wrapper:
                    img = img_wrapper.find('img', class_='blog_tile_img')
                    if img and img.get('src'):
                        posts_data[int(post_id)] = img['src']
        
        print(f"Page {page}: found {len(links)} posts")
        time.sleep(0.5)  # Be nice to the server
    
    return posts_data

def scrape_post(post_id):
    """Scrape a single blog post"""
    url = f"https://mynzhome.com/blog_post/{post_id}"
    print(f"Scraping post {post_id}...")
    
    try:
        response = requests.get(url)
        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Extract title from h2 in blog_headings_container
        headings_container = soup.find('div', class_='blog_headings_container')
        title = "Untitled"
        if headings_container:
            title_elem = headings_container.find('h2')
            title = title_elem.text.strip() if title_elem else f"Post {post_id}"
        
        # Extract subheading from h3 with class blog_sub_heading
        subheading_elem = soup.find('h3', class_='blog_sub_heading')
        subheading = subheading_elem.text.strip() if subheading_elem else None
        
        # Extract author and date
        author = "Peter Matthews"
        date = None
        if headings_container:
            author_elem = headings_container.find('span', class_='blog_credit')
            if author_elem:
                author = author_elem.text.strip()
            date_elem = headings_container.find('span', class_='blog_date')
            if date_elem:
                date = date_elem.text.strip()
        
        # Extract main content from div with class blog_content
        content_div = soup.find('div', class_='blog_content')
        content = ""
        if content_div:
            # Get all paragraphs and preserve HTML structure
            content = str(content_div)
        
        # Extract featured image
        img_elem = soup.find('img', src=lambda x: x and '/img/blog_img/' in x)
        featured_image = img_elem['src'] if img_elem else None
        
        # Extract category from navigation - look for links in sidenav
        category = None
        # We'll derive category from the content or leave it null for now
        
        # Extract tags
        tags = []
        tag_links = soup.find_all('a', href=lambda x: x and '/filterBlogByTagId/' in x)
        tags = [link.text.strip() for link in tag_links]
        
        return {
            'id': post_id,
            'title': title,
            'sub_heading': subheading,
            'content': content,
            'author': author,
            'category': category,
            'featured_image': featured_image,
            'tags': tags,
            'created_at': date
        }
        
    except Exception as e:
        print(f"Error scraping post {post_id}: {e}")
        return None

def main():
    print("Getting all post IDs and images...")
    posts_images = get_post_ids_and_images()
    post_ids = sorted(posts_images.keys())
    print(f"\nFound {len(post_ids)} posts total: {post_ids[0]} to {post_ids[-1]}")
    
    print("\nScraping posts...")
    posts = []
    for post_id in post_ids:
        post = scrape_post(post_id)
        if post:
            # Override the featured image with the one from the listing page
            post['featured_image'] = posts_images.get(post_id, post['featured_image'])
            posts.append(post)
        time.sleep(0.5)  # Be nice to the server
    
    # Save to JSON
    output_file = 'blog_posts.json'
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(posts, f, indent=2, ensure_ascii=False)
    
    print(f"\n✓ Scraped {len(posts)} posts")
    print(f"✓ Saved to {output_file}")
    
    # Print summary
    categories = set(p['category'] for p in posts if p['category'])
    all_tags = set()
    for p in posts:
        all_tags.update(p['tags'])
    
    print(f"\nCategories found: {sorted(categories)}")
    print(f"Tags found: {len(all_tags)} unique tags")

if __name__ == '__main__':
    main()
