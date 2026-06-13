<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Safe check or create admin
        $admin = User::where('email', 'admin@blog.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@blog.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]);
        }

        // Safe check or create reader
        $reader = User::where('email', 'reader@blog.com')->first();
        if (!$reader) {
            User::create([
                'name' => 'Reader User',
                'email' => 'reader@blog.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]);
        }

        // Clear existing blogs to start fresh with professional ones
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Blog::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. PDF.ai Review
        \App\Models\Blog::create([
            'title' => 'PDF.ai Review: Chat with Your PDF Documents Instantly',
            'slug' => 'pdf-ai-review-chat-with-your-pdf-documents-instantly',
            'content' => '<p>Imagine being able to ask a document questions and get instant, accurate answers. That is precisely what <strong>PDF.ai</strong> offers. It is an AI-powered platform that transforms static PDF documents into dynamic, conversational interfaces.</p>
<h2>Key Features of PDF.ai</h2>
<ul>
  <li><strong>Document Chat:</strong> Directly chat with any uploaded PDF document using natural language queries.</li>
  <li><strong>Accurate Citations:</strong> Every response includes direct citations and references to the exact page/paragraph of the source document.</li>
  <li><strong>Multi-file Support:</strong> Search across multiple PDFs simultaneously for unified document intelligence.</li>
  <li><strong>Secure and Private:</strong> Enterprise-grade security ensures your confidential documents remain private and protected.</li>
</ul>
<h2>Who is PDF.ai For?</h2>
<p>Whether you are a researcher reviewing academic papers, a lawyer dissecting contract terms, or a student analyzing textbook chapters, PDF.ai dramatically accelerates your reading and comprehension speeds. It turns hours of manual scanning into seconds of automated query response.</p>
<p>Get started today by signing up through our partner link and explore the power of conversational document search!</p>',
            'user_id' => $admin->id,
            'views' => rand(500, 1500),
            'is_published' => true,
            'category' => 'PDF Tools',
            'affiliate_link' => 'https://pdf.ai?via=antigravity',
            'image' => 'blogs/pdf_ai.png',
            'created_at' => now()->subDays(3),
        ]);

        // 2. Jasper AI Review
        \App\Models\Blog::create([
            'title' => 'Jasper AI Review: The Ultimate AI Writing Assistant for Teams',
            'slug' => 'jasper-ai-review-the-ultimate-ai-writing-assistant-for-teams',
            'content' => '<p>In the crowded market of AI copywriters, <strong>Jasper AI</strong> stands out as the gold standard for enterprise marketing teams. Known for its tone-of-voice alignment and robust template library, Jasper helps content creators scale production without losing brand identity.</p>
<h2>Why Choose Jasper AI?</h2>
<ul>
  <li><strong>Brand Voice Adaptation:</strong> Train Jasper on your style guide, product information, and brand tone so it writes exactly like your team.</li>
  <li><strong>Jasper Campaigns:</strong> Generate full-scale multi-channel marketing campaigns (emails, blog posts, social media, ads) in minutes with a single brief.</li>
  <li><strong>AI Image Generator:</strong> Built-in art generation tool to create custom stock-style visuals on the fly.</li>
  <li><strong>Integrations:</strong> Seamless integration with Webflow, Google Docs, and Chrome extensions.</li>
</ul>
<h2>Pricing and Value</h2>
<p>While Jasper is on the premium side compared to base models, the efficiency gains for marketing departments are unmatched. The collaborative features allow editing, reviewing, and approving content at scale.</p>
<p>Click below to sign up and get an exclusive free trial through our referral link!</p>',
            'user_id' => $admin->id,
            'views' => rand(800, 2000),
            'is_published' => true,
            'category' => 'AI Tools',
            'affiliate_link' => 'https://jasper.ai?fpr=antigravity',
            'image' => 'blogs/jasper_ai.png',
            'created_at' => now()->subDays(2),
        ]);

        // 3. ChatGPT Plus vs Claude 3.5 Sonnet
        \App\Models\Blog::create([
            'title' => 'ChatGPT Plus vs Claude 3.5 Sonnet: The Premium AI Showdown',
            'slug' => 'chatgpt-plus-vs-claude-3-5-sonnet-the-premium-ai-showdown',
            'content' => '<p>Deciding which premium AI chatbot to subscribe to can be challenging. <strong>ChatGPT Plus (powered by GPT-4o)</strong> and <strong>Claude 3.5 Sonnet</strong> represent the pinnacle of conversational AI. Let\'s break down which one deserves your $20/month subscription.</p>
<h2>Feature Comparison</h2>
<ul>
  <li><strong>Coding & Logic:</strong> Claude 3.5 Sonnet consistently outperforms GPT-4o on programming tasks, complex reasoning, and structured software design.</li>
  <li><strong>Speed and Usability:</strong> ChatGPT Plus offers faster response generation times and integrates seamlessly with search engines, voice mode, and DALL-E 3 image generation.</li>
  <li><strong>Context Window:</strong> Claude offers a massive 200k token context window with the "Artifacts" interactive view, allowing you to preview and edit code or SVGs in real time.</li>
  <li><strong>Data Analysis:</strong> ChatGPT\'s Advanced Data Analysis tool remains superior for running Python code and processing spreadsheets in the cloud.</li>
</ul>
<h2>The Verdict</h2>
<p>If you are a developer, technical writer, or creative designer, <strong>Claude 3.5 Sonnet</strong> is currently the superior model. For general tasks, web browsing, and mobile voice communication, <strong>ChatGPT Plus</strong> remains the convenience king.</p>',
            'user_id' => $admin->id,
            'views' => rand(1200, 3000),
            'is_published' => true,
            'category' => 'Comparisons',
            'affiliate_link' => 'https://anthropic.com',
            'image' => 'blogs/claude_chatgpt.png',
            'created_at' => now()->subDays(1),
        ]);

        // 4. Copy.ai Review
        \App\Models\Blog::create([
            'title' => 'Copy.ai Review: Automating Your Sales and Marketing Workflows',
            'slug' => 'copy-ai-review-automating-your-sales-and-marketing-workflows',
            'content' => '<p>Modern businesses are moving away from simple AI prompts toward automated workflows. <strong>Copy.ai</strong> has evolved from a basic copywriting tool into a powerful enterprise workflow automation engine that scales operations.</p>
<h2>Key Features of Copy.ai</h2>
<ul>
  <li><strong>Workflows Builder:</strong> Connect multiple prompts and steps together to create automated pipelines, such as converting a new webinar recording into blog posts, tweets, and emails.</li>
  <li><strong>Infobase:</strong> A centralized repository to upload company information, templates, and style guidelines that the AI can reference across workflows.</li>
  <li><strong>Sales OS:</strong> Automate outreach emails, lead research, and CRM data enrichment inside a single platform.</li>
  <li><strong>Collaborative Hub:</strong> Share custom workflows and templates across departments to ensure team alignment.</li>
</ul>
<h2>Is Copy.ai Right for You?</h2>
<p>If you want to move beyond copy-pasting prompts and start automating repetitive sales and marketing actions, Copy.ai is the ideal solution. It integrates with your tech stack to run processes automatically behind the scenes.</p>
<p>Try Copy.ai for free today by clicking our referral link below!</p>',
            'user_id' => $admin->id,
            'views' => rand(300, 1000),
            'is_published' => true,
            'category' => 'Business',
            'affiliate_link' => 'https://copy.ai?lmref=antigravity',
            'image' => 'blogs/copy_ai.png',
            'created_at' => now(),
        ]);
    }
}
