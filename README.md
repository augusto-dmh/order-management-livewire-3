## Order Management with Dynamic Livewire Components (Including Report/Charts)

### Why this course was taken?
<ul>
<li>Learn better - i already used Livewire, but only for a few features at work and in university projects - how to integrate Laravel/Livewire with third-party libraries involving report-generation/charts - latests tasks on my internship involved these kind of features.</li>
<li>Understand better the relationship between AlpineJS and Livewire - these two are used in the "TALL" stack</li>
<li>See good practices from Povillas (the instructor) on how he works around limitations of integration between libraries and the framework (e.g: CKEditor, Select2...)</li>
<li>Get aware of multiple dynamic behaviors that can be implemented in UI - multiple would be showed in basically each feature in this course - and if it is worthwhile or not (e.g: sorting on drag-and-drop is too much overhead for a result that almost always useless... Sorting on column though is interesting in some scenarios.</li>
</ul>

### About the Application Builded (including server-side)
<li>Order management for small-to-medium businesses - no deep statistics or preditive analysis</li>
<li>Session-based authentication from Breeze</li>
<li>TALL Stack (Tailwind, Alpine, Laravel and Livewire)/</li>
<li>SQLite</li>

### Methodology
<p>The solution i usually take in the "project-courses" is mentioned below.</p>
<p>PS: In this course specifically, some of features from third-party libraries i wasn't aware how to make them work on my own and struggle to find how. In these difficulties i've asked in the instructor's community about information and was guided by answers from more experienced developers. So that's why a lot of the classes mentioned in the commits have the "instructor's version" and no more versions about it.</p>
<ul>
<li>Reach myself a solution - with some <i>extra glow</i> or not - for the lesson based on the professor's solution - if any</li>
<li>Compare my approach to the professor's</li>
<li>Implement professor's solution</li>
<li>Repeat last steps with the next class</li>
</ul>

### Why this approach?

<p>Currently in my work i deal with projects involving multiple people, and specifically applications that either are mantained for a long time (20-30y), or on the build of newer version of them using modern frameworks.</p>
<p>Frequently i don't get solutions for the problems i face in a pass of magic (the most near to this is, by understanding the business rule around the feature, get a base-solution from LLMs). So i found better, after the initial configs have been set - e.g: how the routing is dealt -, to try my own approach in each class - each class is a feature generally.</p> 
<p>And besides that, why implement my own version based on previous versions of features based on <i>the professor's</i> version? Well, simlar reason: working with others implies developing based on previous code not always written by yourself, so developing based on the professor's approach intends to simulate the real-world scenario i pass daily in work.</p>

### What the "Extra Glow" Mean?

<p>My own solution can include additional aspect not asked by the professor. Below are a few examples from this project:</p>
<ul>
<li>Take approaches to solve problems solely with Laravel/Livewire in situations that the instructor would add up AlpineJS</li>
<li>Implement error-handling in places where it lacked in instructor's version</li>
<li>Couple small components to reduce overhead without damaging maintenancy - check commit f64250d00b544fbf634962cdb39c1a98848c4f74</li>
</ul>

### Why a lot of "mixed with instructor's version" and some solely "my version" are mentioned in the commits?
<p>Some of the approaches taken by the instructor really didn't provided any improvement and i decided not to take strictly my methodology, avoiding letting all "pure" changes by the instructor in each step, mixing up with some decisions of mine.</p>
<p>E.g: "Delete selected" -> I'd disable this button and change its style based on `empty($productsSelectedToBeDeletedIds)`; the instructor would create a computed property in the component to calculate this upon another property, `selected`. My naming is better, the computed property doesn't provide any improvement due to perform a simple calculation.</p>
