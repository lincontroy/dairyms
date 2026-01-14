<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Management System - User Manual</title>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
            padding: 20px;
        }

        .manual-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Table of Contents */
        .toc {
            background: #f1f5f9;
            padding: 30px;
            border-bottom: 2px solid #e2e8f0;
        }

        .toc h2 {
            color: #1e3c72;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #cbd5e1;
        }

        .toc ul {
            columns: 2;
            column-gap: 40px;
        }

        .toc li {
            margin-bottom: 10px;
            break-inside: avoid;
        }

        .toc a {
            color: #2d3748;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .toc a:hover {
            background: #e2e8f0;
            color: #1e3c72;
            transform: translateX(5px);
        }

        .toc a::before {
            content: "📋";
            margin-right: 10px;
        }

        /* Main Content */
        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 50px;
            scroll-margin-top: 20px;
        }

        .section h2 {
            color: #1e3c72;
            font-size: 1.8rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
        }

        .section h2::before {
            margin-right: 15px;
            font-size: 1.5rem;
        }

        .overview h2::before { content: "🏢"; }
        .roles h2::before { content: "👥"; }
        .animals h2::before { content: "🐄"; }
        .milk h2::before { content: "🥛"; }
        .breeding h2::before { content: "🤰"; }
        .health h2::before { content: "🏥"; }
        .suppliers h2::before { content: "🚚"; }
        .expenses h2::before { content: "💰"; }
        .reports h2::before { content: "📊"; }

        /* Permissions Grid */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .role-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #3b82f6;
        }

        .role-card h3 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
        }

        .role-card.admin { border-left: 5px solid #ef4444; }
        .role-card.manager { border-left: 5px solid #3b82f6; }
        .role-card.vet { border-left: 5px solid #10b981; }
        .role-card.staff { border-left: 5px solid #f59e0b; }

        .permission-list {
            list-style: none;
        }

        .permission-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
        }

        .permission-list li:last-child {
            border-bottom: none;
        }

        .permission-list li::before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
            margin-right: 10px;
            width: 20px;
            height: 20px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .permission-list li.denied::before {
            content: "✗";
            color: #ef4444;
            background: #fee2e2;
        }

        /* Feature Cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .feature-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-card h3 {
            color: #1e3c72;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .feature-card h3::before {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .steps {
            list-style: none;
            counter-reset: step-counter;
        }

        .steps li {
            counter-increment: step-counter;
            padding: 15px 0 15px 50px;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .steps li:last-child {
            border-bottom: none;
        }

        .steps li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Workflow Section */
        .workflow {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
            gap: 15px;
        }

        .workflow-step {
            background: #3b82f6;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            position: relative;
            text-align: center;
            min-width: 120px;
        }

        .workflow-step::after {
            content: "→";
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.5rem;
        }

        .workflow-step:last-child::after {
            display: none;
        }

        /* Status Indicators */
        .status-indicators {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .status {
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status.pending { background: #fef3c7; color: #92400e; }
        .status.approved { background: #d1fae5; color: #065f46; }
        .status.rejected { background: #fee2e2; color: #991b1b; }
        .status.recorded { background: #e0e7ff; color: #3730a3; }

        /* Footer */
        .footer {
            background: #1e293b;
            color: #cbd5e1;
            padding: 30px;
            text-align: center;
            margin-top: 40px;
        }

        .footer p {
            margin: 10px 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .toc ul {
                columns: 1;
            }
            
            .feature-grid,
            .permissions-grid {
                grid-template-columns: 1fr;
            }
            
            .workflow-step::after {
                display: none;
            }
            
            .workflow {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .manual-container {
                box-shadow: none;
            }
            
            .toc a:hover {
                background: transparent;
                transform: none;
            }
            
            .role-card:hover,
            .feature-card:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="manual-container">
        <!-- Header -->
        <div class="header">
            <h1>Dairy Management System</h1>
            <p class="subtitle">Comprehensive User Manual & Role-Based Guide</p>
            <p style="margin-top: 20px; opacity: 0.8;">Version 2.0 • Last Updated: December 2023</p>
        </div>

        <!-- Table of Contents -->
        <div class="toc">
            <h2>📖 Table of Contents</h2>
            <ul>
                <li><a href="#overview">System Overview</a></li>
                <li><a href="#roles">User Roles & Permissions</a></li>
                <li><a href="#animals">Animal Management</a></li>
                <li><a href="#milk">Milk Production Management</a></li>
                <li><a href="#breeding">Breeding Records</a></li>
                <li><a href="#health">Health Management</a></li>
                <li><a href="#suppliers">Supplier Management</a></li>
                <li><a href="#expenses">Expense Management</a></li>
                <li><a href="#reports">Reporting & Analytics</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Section 1: Overview -->
            <section id="overview" class="section overview">
                <h2>System Overview</h2>
                <p>The Dairy Management System is a comprehensive platform designed to streamline all aspects of dairy farming operations. From animal tracking to financial management, the system provides tools for efficient farm management.</p>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>🏭 Core Features</h3>
                        <ul class="permission-list">
                            <li>Animal Registration & Lifecycle Tracking</li>
                            <li>Milk Production Monitoring (3 sessions/day)</li>
                            <li>Breeding & Pregnancy Management</li>
                            <li>Health Record Keeping with Withdrawal Periods</li>
                            <li>Supplier Milk Collection & Payment Processing</li>
                            <li>Expense Tracking & Budget Management</li>
                            <li>Role-based Access Control System</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h3>🎯 Key Benefits</h3>
                        <ul class="permission-list">
                            <li>Real-time Production Monitoring</li>
                            <li>Automated Payment Processing</li>
                            <li>Health & Breeding Alerts</li>
                            <li>Financial Reporting & Analytics</li>
                            <li>Mobile-friendly Interface</li>
                            <li>Data Security & Backup</li>
                            <li>Multi-user Collaboration</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 2: Roles -->
            <section id="roles" class="section roles">
                <h2>User Roles & Permissions</h2>
                <p>The system uses role-based access control to ensure security and proper workflow management. Each role has specific permissions tailored to their responsibilities.</p>
                
                <div class="permissions-grid">
                    <div class="role-card admin">
                        <h3>👑 Administrator</h3>
                        <p style="color: #666; margin-bottom: 15px;">Full system control and management</p>
                        <ul class="permission-list">
                            <li>Create, edit, delete all records</li>
                            <li>Manage users and permissions</li>
                            <li>Approve/reject milk records</li>
                            <li>Approve supplier payments</li>
                            <li>Approve expenses</li>
                            <li>View all financial reports</li>
                            <li>System configuration</li>
                        </ul>
                    </div>
                    
                    <div class="role-card manager">
                        <h3>📋 Manager</h3>
                        <p style="color: #666; margin-bottom: 15px;">Operational management and oversight</p>
                        <ul class="permission-list">
                            <li>View all animal records</li>
                            <li>Record and edit milk production</li>
                            <li class="denied">Auto-approve milk records</li>
                            <li>Manage breeding records</li>
                            <li>View/manage health records</li>
                            <li>Record milk supplies</li>
                            <li>Create expense records</li>
                            <li class="denied">Cannot approve payments</li>
                            <li class="denied">Cannot manage users</li>
                        </ul>
                    </div>
                    
                    <div class="role-card vet">
                        <h3>🐾 Veterinarian</h3>
                        <p style="color: #666; margin-bottom: 15px;">Health and breeding focus</p>
                        <ul class="permission-list">
                            <li>View animal records</li>
                            <li>Create/edit health records</li>
                            <li>Manage breeding records</li>
                            <li>View milk production data</li>
                            <li class="denied">Cannot manage finances</li>
                            <li class="denied">Cannot approve payments</li>
                            <li class="denied">Cannot manage suppliers</li>
                        </ul>
                    </div>
                    
                    <div class="role-card staff">
                        <h3>👨‍🌾 Staff</h3>
                        <p style="color: #666; margin-bottom: 15px;">Basic operations and recording</p>
                        <ul class="permission-list">
                            <li>Record milk production</li>
                            <li class="denied">Requires approval for records</li>
                            <li>View assigned animals</li>
                            <li>Submit health observations</li>
                            <li class="denied">Cannot edit approved records</li>
                            <li class="denied">No financial access</li>
                            <li class="denied">No approval permissions</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Section 3: Animals -->
            <section id="animals" class="section animals">
                <h2>Animal Management</h2>
                <p>Complete lifecycle management for all animals in the herd with detailed pedigree tracking.</p>
                
                <div class="feature-card">
                    <h3>Adding New Animals</h3>
                    <ol class="steps">
                        <li>Navigate to <strong>Animals > Add New</strong></li>
                        <li>Fill in required fields:
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li><code>animal_id</code> - Unique identification</li>
                                <li><code>name</code> - Animal name</li>
                                <li><code>ear_tag</code> - Physical tag number</li>
                                <li><code>breed</code> - Breed type</li>
                                <li><code>date_of_birth</code> - Birth date</li>
                                <li><code>sex</code> - Male/Female</li>
                                <li><code>dam_id/sire_id</code> - Parent references</li>
                            </ul>
                        </li>
                        <li>Set initial <code>status</code> (calf, heifer, lactating, dry, etc.)</li>
                        <li>Save record - system generates unique ID if not provided</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Animal Status Tracking</h3>
                    <p>Monitor animal status throughout their lifecycle:</p>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px;">
                        <span style="background: #dbeafe; padding: 5px 10px; border-radius: 5px;">🐣 Calf</span>
                        <span style="background: #fef3c7; padding: 5px 10px; border-radius: 5px;">👧 Heifer</span>
                        <span style="background: #d1fae5; padding: 5px 10px; border-radius: 5px;">🤰 Pregnant</span>
                        <span style="background: #e0e7ff; padding: 5px 10px; border-radius: 5px;">🥛 Lactating</span>
                        <span style="background: #f3f4f6; padding: 5px 10px; border-radius: 5px;">🌾 Dry</span>
                        <span style="background: #fce7f3; padding: 5px 10px; border-radius: 5px;">🎯 Breeding</span>
                    </div>
                </div>
            </section>

            <!-- Section 4: Milk Production -->
            <section id="milk" class="section milk">
                <h2>Milk Production Management</h2>
                <p>Three-session daily milk recording with automated calculations and approval workflow.</p>
                
                <div class="workflow">
                    <div class="workflow-step">Morning Milking<br>(6:00 AM)</div>
                    <div class="workflow-step">Afternoon Milking<br>(1:00 PM)</div>
                    <div class="workflow-step">Evening Milking<br>(6:00 PM)</div>
                </div>
                
                <div class="feature-card">
                    <h3>Recording Milk Production</h3>
                    <ol class="steps">
                        <li>Go to <strong>Milk Production > Record Session</strong></li>
                        <li>Select animal from active lactating list</li>
                        <li>Enter yields for each session:
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li><code>morning_yield</code> - Liters</li>
                                <li><code>afternoon_yield</code> - Liters</li>
                                <li><code>evening_yield</code> - Liters</li>
                            </ul>
                        </li>
                        <li>System automatically calculates <code>total_yield</code></li>
                        <li>Select <code>lactation_number</code> (auto-suggested)</li>
                        <li>Add notes if needed</li>
                        <li>Submit for approval</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Approval Workflow</h3>
                    <div class="status-indicators">
                        <div class="status pending">Pending Approval</div>
                        <div class="status approved">Approved</div>
                        <div class="status rejected">Rejected</div>
                    </div>
                    <ul class="permission-list" style="margin-top: 20px;">
                        <li><strong>Staff:</strong> Records require manager/admin approval</li>
                        <li><strong>Managers/Admins:</strong> Records auto-approved</li>
                        <li>Pending records appear in approval queue</li>
                        <li>Approvers can add comments when rejecting</li>
                    </ul>
                </div>
            </section>

            <!-- Section 5: Breeding -->
            <section id="breeding" class="section breeding">
                <h2>Breeding Records Management</h2>
                <p>Track breeding cycles, pregnancy diagnosis, and calving outcomes.</p>
                
                <div class="feature-card">
                    <h3>Breeding Process</h3>
                    <ol class="steps">
                        <li><strong>Service Recording:</strong> Date, method, technician</li>
                        <li><strong>Pregnancy Diagnosis:</strong> Result and date</li>
                        <li><strong>Expected Calving:</strong> Auto-calculated dates</li>
                        <li><strong>Actual Calving:</strong> Record outcome and date</li>
                        <li><strong>Follow-up:</strong> Health checks for dam and calf</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Key Fields</h3>
                    <ul class="permission-list">
                        <li><code>breeding_method</code> - Natural/AI</li>
                        <li><code>bull_semen_id</code> - For AI records</li>
                        <li><code>pregnancy_result</code> - Positive/Negative</li>
                        <li><code>expected_calving_date</code> - Auto-calculated</li>
                        <li><code>calving_outcome</code> - Live/Stillborn/Complications</li>
                        <li><code>technician</code> - Who performed service</li>
                    </ul>
                </div>
            </section>

            <!-- Section 6: Health -->
            <section id="health" class="section health">
                <h2>Health Management</h2>
                <p>Comprehensive health tracking with medication withdrawal periods.</p>
                
                <div class="feature-card">
                    <h3>Creating Health Records</h3>
                    <ol class="steps">
                        <li>Select animal from herd list</li>
                        <li>Record diagnosis and clinical signs</li>
                        <li>Enter treatment details:
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>Drug name and dosage</li>
                                <li>Administration route</li>
                                <li>Treatment duration</li>
                            </ul>
                        </li>
                        <li>Set withdrawal periods:
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li><code>milk_withdrawal_days</code></li>
                                <li><code>meat_withdrawal_days</code></li>
                            </ul>
                        </li>
                        <li>Record veterinarian and outcome</li>
                        <li>Save - system tracks withdrawal dates</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Withdrawal Period Alerts</h3>
                    <ul class="permission-list">
                        <li>System highlights animals under withdrawal</li>
                        <li>Automatic exclusion from milk collection</li>
                        <li>Email/SMS alerts to managers</li>
                        <li>Clear indication when withdrawal ends</li>
                        <li>Historical tracking for audits</li>
                    </ul>
                </div>
            </section>

            <!-- Section 7: Suppliers -->
            <section id="suppliers" class="section suppliers">
                <h2>Supplier Management</h2>
                <p>Track milk collection from external suppliers with automated payment processing.</p>
                
                <div class="feature-card">
                    <h3>Recording Milk Supply</h3>
                    <ol class="steps">
                        <li>Select supplier from active list</li>
                        <li>Enter <code>quantity_liters</code> supplied</li>
                        <li>System auto-fills <code>rate_per_liter</code></li>
                        <li>Record any <code>waste_liters</code></li>
                        <li>System calculates <code>total_amount</code></li>
                        <li>Save - auto-generates payment record</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Payment Processing</h3>
                    <div class="workflow">
                        <div class="workflow-step">Milk Supply Recorded</div>
                        <div class="workflow-step">Auto-generate Payment</div>
                        <div class="workflow-step">Admin Approval</div>
                        <div class="workflow-step">Payment Processed</div>
                    </div>
                    
                    <ul class="permission-list" style="margin-top: 20px;">
                        <li>Auto-generated payments marked "pending"</li>
                        <li>Only Admin can approve payments</li>
                        <li>System tracks payment history</li>
                        <li>Balance calculations for each supplier</li>
                    </ul>
                </div>
            </section>

            <!-- Section 8: Expenses -->
            <section id="expenses" class="section expenses">
                <h2>Expense Management</h2>
                <p>Track all farm expenses with category management and approval workflow.</p>
                
                <div class="feature-card">
                    <h3>Recording Expenses</h3>
                    <ol class="steps">
                        <li>Navigate to <strong>Finance > Add Expense</strong></li>
                        <li>Select expense category:
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>Feeds & Supplements</li>
                                <li>Veterinary & Medicines</li>
                                <li>Labor & Salaries</li>
                                <li>Utilities & Maintenance</li>
                                <li>Equipment & Supplies</li>
                                <li>Transport & Fuel</li>
                                <li>Other Expenses</li>
                            </ul>
                        </li>
                        <li>Enter amount and description</li>
                        <li>Select payment method</li>
                        <li>Attach receipt/reference number</li>
                        <li>Submit for approval if required</li>
                    </ol>
                </div>
                
                <div class="feature-card">
                    <h3>Approval Permissions</h3>
                    <ul class="permission-list">
                        <li><strong>Staff:</strong> Cannot record expenses</li>
                        <li><strong>Managers:</strong> Can record but not approve</li>
                        <li><strong>Admin Only:</strong> Can approve expenses</li>
                        <li>Pending expenses appear in approval queue</li>
                        <li>Email notifications for approvals</li>
                    </ul>
                </div>
            </section>

            <!-- Section 9: Reports -->
            <section id="reports" class="section reports">
                <h2>Reporting & Analytics</h2>
                <p>Comprehensive reporting system for data analysis and decision making.</p>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>Daily Reports</h3>
                        <ul class="permission-list">
                            <li>Milk Production Summary</li>
                            <li>Health Check Alerts</li>
                            <li>Breeding Due Dates</li>
                            <li>Supplier Deliveries</li>
                            <li>Daily Expenses</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h3>Monthly Reports</h3>
                        <ul class="permission-list">
                            <li>Production Trends</li>
                            <li>Financial Summary</li>
                            <li>Health Incidence Report</li>
                            <li>Breeding Success Rates</li>
                            <li>Supplier Performance</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h3>Analytics Tools</h3>
                        <ul class="permission-list">
                            <li>Production per Animal</li>
                            <li>Cost per Liter Analysis</li>
                            <li>Breeding Efficiency</li>
                            <li>Health Cost Tracking</li>
                            <li>Supplier Cost Analysis</li>
                        </ul>
                    </div>
                    
                    <div class="feature-card">
                        <h3>Export Options</h3>
                        <ul class="permission-list">
                            <li>PDF Reports</li>
                            <li>Excel Export</li>
                            <li>CSV Data Dumps</li>
                            <li>Print-friendly Views</li>
                            <li>Email Reports</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Dairy Management System © 2023</strong></p>
            <p>This manual is designed for printing and PDF export. For the latest updates, check the online help system.</p>
            <p style="margin-top: 20px; font-size: 0.9rem; opacity: 0.7;">
                System Version: 2.0 | Database Schema: Laravel Eloquent | 
                Role-based Access Control | PDF Export Compatible
            </p>
        </div>
    </div>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add print button functionality
        const printButton = document.createElement('button');
        printButton.innerHTML = '🖨️ Print Manual';
        printButton.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1e3c72;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        `;
        
        printButton.addEventListener('mouseenter', () => {
            printButton.style.transform = 'translateY(-2px)';
            printButton.style.boxShadow = '0 6px 16px rgba(0,0,0,0.3)';
        });
        
        printButton.addEventListener('mouseleave', () => {
            printButton.style.transform = 'translateY(0)';
            printButton.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
        });
        
        printButton.addEventListener('click', () => {
            window.print();
        });
        
        document.body.appendChild(printButton);

        // Add active section highlighting
        const sections = document.querySelectorAll('.section');
        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    // You could add active class styling here
                    // entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>