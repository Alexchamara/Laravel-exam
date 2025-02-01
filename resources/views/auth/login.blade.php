@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
        <div class="text-center">
            <h2 class="text-3xl font-bold">Sign in to your account</h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <input id="email" name="email" type="email" required 
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                        placeholder="Email address" 
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                        placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if (session('status'))
                <div class="text-sm text-red-500">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('register.page') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                    I don't have an account
                </a>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection