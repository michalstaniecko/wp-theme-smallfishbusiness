#!/bin/bash

# Wait for WordPress to be ready
echo "Waiting for WordPress..."
sleep 10

# Install WordPress if not installed
wp core is-installed 2>/dev/null || wp core install \
  --url="http://localhost:8080" \
  --title="Small Fish Business" \
  --admin_user="admin" \
  --admin_password="admin" \
  --admin_email="admin@example.com" \
  --skip-email

# Activate theme
wp theme activate smallfishbusiness

# Create categories
echo "Creating categories..."
wp term create category "Business Tips" --slug=business-tips 2>/dev/null || echo "Category 'Business Tips' already exists"
wp term create category "Marketing" --slug=marketing 2>/dev/null || echo "Category 'Marketing' already exists"
wp term create category "Finance" --slug=finance 2>/dev/null || echo "Category 'Finance' already exists"
wp term create category "Productivity" --slug=productivity 2>/dev/null || echo "Category 'Productivity' already exists"

# Delete default "Hello World" post
wp post delete 1 --force 2>/dev/null || true

# Delete default sample page
wp post delete 2 --force 2>/dev/null || true

# Generate lorem ipsum posts
echo "Creating test posts..."

# Post 1
wp post create --post_type=post --post_status=publish \
  --post_title="10 Essential Tips for Small Business Success" \
  --post_content='<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Understanding Your Market</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Building Customer Relationships</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Communication Strategies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Financial Planning</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Budget Management</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus.</p>
<!-- /wp:paragraph -->' --post_category=business-tips

# Post 2
wp post create --post_type=post --post_status=publish \
  --post_title="Digital Marketing Strategies for 2024" \
  --post_content='<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras porttitor metus justo, ut fringilla velit fermentum a.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Social Media Marketing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Platform Selection</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Content Marketing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Pellentesque in ipsum id orci porta dapibus. Curabitur aliquet quam id dui posuere blandit. Mauris blandit aliquet elit.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Blog Strategy</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nulla quis lorem ut libero malesuada feugiat. Donec rutrum congue leo eget malesuada. Proin eget tortor risus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Email Marketing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.</p>
<!-- /wp:paragraph -->' --post_category=marketing

# Post 3
wp post create --post_type=post --post_status=publish \
  --post_title="Managing Cash Flow in Your Small Business" \
  --post_content='<!-- wp:paragraph -->
<p>Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Nulla quis lorem ut libero malesuada feugiat. Proin eget tortor risus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Understanding Cash Flow Basics</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Income vs Expenses</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Donec sollicitudin molestie malesuada. Curabitur aliquet quam id dui posuere blandit. Vestibulum ac diam sit amet quam vehicula.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Forecasting and Budgeting</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Nulla porttitor accumsan tincidunt. Cras ultricies ligula sed magna dictum porta.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Monthly Review Process</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Vivamus suscipit tortor eget felis porttitor volutpat.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Emergency Fund Planning</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</p>
<!-- /wp:paragraph -->' --post_category=finance

# Post 4
wp post create --post_type=post --post_status=publish \
  --post_title="Productivity Hacks for Entrepreneurs" \
  --post_content='<!-- wp:paragraph -->
<p>Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis in faucibus orci luctus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Time Management Techniques</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Nulla quis lorem ut libero malesuada feugiat. Donec rutrum congue leo eget malesuada.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>The Pomodoro Method</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Tools and Apps</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Automation Tips</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nulla quis lorem ut libero malesuada feugiat. Donec rutrum congue leo eget malesuada. Vestibulum ante ipsum primis in faucibus orci luctus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Work-Life Balance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Cras ultricies ligula sed magna dictum porta. Nulla porttitor accumsan tincidunt. Praesent sapien massa, convallis a pellentesque nec.</p>
<!-- /wp:paragraph -->' --post_category=productivity

# Post 5
wp post create --post_type=post --post_status=publish \
  --post_title="Building a Strong Brand Identity" \
  --post_content='<!-- wp:paragraph -->
<p>Nulla quis lorem ut libero malesuada feugiat. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Vivamus magna justo, lacinia eget consectetur sed.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Defining Your Brand Values</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Mission Statement</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Pellentesque in ipsum id orci porta dapibus.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Visual Identity</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Donec sollicitudin molestie malesuada. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur non nulla sit amet nisl tempus convallis.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Logo and Color Palette</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Nulla porttitor accumsan tincidunt. Cras ultricies ligula sed magna dictum porta.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Brand Voice</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Cras ultricies ligula sed magna dictum porta. Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Tone and Messaging</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Proin eget tortor risus. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Curabitur aliquet quam id dui posuere blandit.</p>
<!-- /wp:paragraph -->' --post_category=marketing,business-tips

echo ""
echo "========================================="
echo "Test content created successfully!"
echo "========================================="
echo "- 4 categories: Business Tips, Marketing, Finance, Productivity"
echo "- 5 posts with lorem ipsum content and H2/H3 headings"
echo ""
echo "WordPress: http://localhost:8080"
echo "Admin: http://localhost:8080/wp-admin"
echo "Login: admin / admin"
echo "========================================="
