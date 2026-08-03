<nav class="border-b border-border px-6">
  <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
    <div>
      <a href="/">
        <img src="/images/idea-logo.png" width="100" height="auto" alt="idea logo">
      </a>
    </div>

    <div class="flex gap-x-5 items-center">
        @auth
            <form method="POST" action="/logout">
                @csrf

                <button>Log out</button>
            </form>
        @endauth


        @guest
            <a href="/login">Sign in</a>
            <a href="/register" class="btn">Register</a>
        @endguest
    </div>

  </div>
</nav>
