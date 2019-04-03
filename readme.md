#Laravel58-shop 系统

##问题1:  
>问题描述:

使用`encore/laravel-admin`构建后台系统,在前后台太都不登录的情况下,首先访问`xxx/admin/auth/login`
随后再次访问`xxx/login`,成功登录之后会自动重定向到`xxx/admin/auth/login`


>问题解决

1.正常用户不会遇到这种情况,随意其实也不算问题,可以选择不修复
2.若强迫症就是要修复可以在`LoginController`中重写`showLoginForm()`判断当前 Session 中的`url.intended`的值是否为后台地址的,如果是则将这个值清除掉

>代码:
`app/Http/Controllers/Auth/LoginController.php`
```
    use Illuminate\Http\Request;
    /**
     * 重写 showLoginForm 方法
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('url.intended')) {
            if (strpos($request->session()->get('url.intended'), '/admin')) {
                $request->session()->forget('url.intended');
            }
        }
        return view('auth.login');
    }
```

>解释

如果直接访问`xx/login`时,在`showLoginForm`中可打印:
```
public function showLoginForm(Request $request){
        dd(session('url.intended'));
    }
```
其结果为 `null`
当首先访问`xx/admin/auth/login`,随后再访问`x/login`时候,我们会得到`xx/admin`
