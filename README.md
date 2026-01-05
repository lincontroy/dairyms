<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DairyFarm Pro - Dairy Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4CAF50;
        }

        h1 {
            color: #2E7D32;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        h2 {
            color: #388E3C;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #C8E6C9;
        }

        h3 {
            color: #43A047;
            margin: 20px 0 10px 0;
        }

        .emoji {
            font-size: 1.2em;
            margin-right: 8px;
        }

        .overview {
            background: #E8F5E9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .feature-card {
            background: white;
            border: 1px solid #C8E6C9;
            border-radius: 8px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-card h4 {
            color: #2E7D32;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        ul {
            list-style-type: none;
            padding-left: 20px;
        }

        li {
            margin: 8px 0;
            padding-left: 25px;
            position: relative;
        }

        li:before {
            content: "✓";
            color: #4CAF50;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        .tech-tag {
            background: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .installation {
            background: #F1F8E9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        pre {
            background: #2d2d2d;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }

        code {
            background: #f1f1f1;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }

        .contact {
            background: #E3F2FD;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            background: #4CAF50;
            color: white;
            border-radius: 12px;
            font-size: 0.8em;
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            h1 {
                font-size: 2em;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><span class="emoji">🐮</span> DairyFarm Pro - Dairy Management System</h1>
            <p>A comprehensive solution for modern dairy farm management</p>
        </header>

        <section class="overview">
            <h2><span class="emoji">📋</span> Overview</h2>
            <p>DairyFarm Pro is a comprehensive dairy management system designed to streamline operations for modern dairy farms. This system helps manage livestock, milk production, inventory, and farm operations through an intuitive interface.</p>
        </section>

        <section class="features">
            <h2><span class="emoji">✨</span> Features</h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <h4><span class="emoji">🐄</span> Livestock Management</h4>
                    <ul>
                        <li><strong>Animal Profiles:</strong> Track individual animal details</li>
                        <li><strong>Health Records:</strong> Monitor vaccinations and treatments</li>
                        <li><strong>Breeding Management:</strong> Track heat cycles and pregnancy</li>
                        <li><strong>Milking History:</strong> Record daily milk yields</li>
                        <li><strong>Lifecycle Tracking:</strong> From calf to mature cow</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h4><span class="emoji">🥛</span> Milk Production</h4>
                    <ul>
                        <li><strong>Daily Collection:</strong> Record milk quantities per session</li>
                        <li><strong>Quality Metrics:</strong> Track fat content, SNF, temperature</li>
                        <li><strong>Yield Analysis:</strong> Generate production reports</li>
                        <li><strong>Milk Dispatch:</strong> Manage distribution to vendors</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h4><span class="emoji">📊</span> Inventory Management</h4>
                    <ul>
                        <li><strong>Feed & Fodder:</strong> Track feed types and consumption</li>
                        <li><strong>Medical Supplies:</strong> Monitor vaccine and medicine stock</li>
                        <li><strong>Equipment:</strong> Maintenance schedules and records</li>
                        <li><strong>Alert System:</strong> Low stock notifications</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h4><span class="emoji">📈</span> Reports & Analytics</h4>
                    <ul>
                        <li><strong>Financial Reports:</strong> Income/expense tracking</li>
                        <li><strong>Production Trends:</strong> Visual charts over time</li>
                        <li><strong>Health Analytics:</strong> Disease patterns analysis</li>
                        <li><strong>Export Capabilities:</strong> PDF/Excel reports</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h4><span class="emoji">👥</span> Employee Management</h4>
                    <ul>
                        <li><strong>Staff Profiles:</strong> Employee details and roles</li>
                        <li><strong>Attendance:</strong> Daily attendance tracking</li>
                        <li><strong>Task Assignment:</strong> Daily duties and responsibilities</li>
                        <li><strong>Payroll:</strong> Salary calculation and management</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <h4><span class="emoji">📱</span> Mobile Access</h4>
                    <ul>
                        <li><strong>Responsive Design:</strong> Works on all devices</li>
                        <li><strong>Mobile App:</strong> Native apps for iOS/Android</li>
                        <li><strong>Offline Mode:</strong> Work without internet</li>
                        <li><strong>Push Notifications:</strong> Instant alerts</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="installation">
            <h2><span class="emoji">🚀</span> Installation</h2>
            <h3>Prerequisites</h3>
            <ul>
                <li>Node.js 14.0 or higher</li>
                <li>MySQL 5.7+ or PostgreSQL 12+</li>
                <li>Python 3.8+ (for backend)</li>
            </ul>

            <h3>Quick Start</h3>
            <pre><code># Clone the repository
git clone https://github.com/yourusername/dairyfarm-pro.git

# Navigate to project directory
cd dairyfarm-pro

# Install dependencies
npm install

# Configure environment variables
cp .env.example .env

# Update .env with your database credentials
DB_HOST=localhost
DB_PORT=3306
DB_NAME=dairyfarm
DB_USER=root
DB_PASSWORD=yourpassword

# Run database migrations
npm run migrate

# Start the development server
npm start</code></pre>
        </section>

        <section class="tech">
            <h2><span class="emoji">🛠️</span> Technology Stack</h2>
            <div class="tech-stack">
                <span class="tech-tag">React 18</span>
                <span class="tech-tag">Node.js</span>
                <span class="tech-tag">Express.js</span>
                <span class="tech-tag">MySQL</span>
                <span class="tech-tag">Python</span>
                <span class="tech-tag">Django</span>
                <span class="tech-tag">Chart.js</span>
                <span class="tech-tag">Bootstrap 5</span>
                <span class="tech-tag">Docker</span>
                <span class="tech-tag">REST API</span>
            </div>
        </section>

        <section class="contact">
            <h2><span class="emoji">📞</span> Contact & Support</h2>
            <p>For support, feature requests, or bug reports:</p>
            <ul>
                <li><strong>Email:</strong> support@dairyfarmpro.com</li>
                <li><strong>Documentation:</strong> <a href="https://docs.dairyfarmpro.com">docs.dairyfarmpro.com</a></li>
                <li><strong>GitHub:</strong> <a href="https://github.com/dairyfarmpro">github.com/dairyfarmpro</a></li>
                <li><strong>Phone:</strong> +1-800-DAIRY-PRO</li>
            </ul>
        </section>

        <footer style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666;">
            <p>© 2024 DairyFarm Pro. All rights reserved. | Version 2.1.0</p>
        </footer>
    </div>

    <script>
        // Simple script for smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderColor = '#4CAF50';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderColor = '#C8E6C9';
            });
        });
    </script>
</body>
</html>