<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-blue-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20 border border-gray-100">
                <div class="max-w-md mx-auto">
                    <div class="flex items-center space-x-5">
                        <div class="h-14 w-14 bg-gray-100 rounded-full flex flex-shrink-0 justify-center items-center text-indigo-600">
                            @if($gateway === 'Stripe')
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24"><path d="M13.911 10.32c-.854-.44-1.63-.615-2.22-.615-.815 0-1.285.348-1.285.918 0 .584.58.855 1.62 1.258 1.455.556 3.444 1.254 3.444 3.513 0 2.457-1.92 4.1-5.14 4.1a10.027 10.027 0 01-3.69-.745l.43-2.617c1.104.584 2.19.86 3.03.86.87 0 1.343-.376 1.343-1.012 0-.675-.626-.95-1.745-1.378C9.526 14.155 7.63 13.43 7.63 11.23c0-2.26 1.83-3.83 4.88-3.83a9.423 9.423 0 013.31.57l-.425 2.35h.516z"/></svg>
                            @else
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20.067 8.178c-.626 3.204-2.85 5.122-6.19 5.122h-.994c-.544 0-.918.344-1.018.84l-1.04 5.222c-.044.204-.194.344-.394.344H7.505c-.328 0-.528-.31-.444-.616l2.126-10.66c.1-.5.474-.842 1.018-.842h3.09c2.345 0 3.96 1.106 4.312 3.59h2.46zM13.298 9.54h-1.61c-.346 0-.585.218-.65.534l-.402 2.01c-.027.13.067.228.2.228h.5c.346 0 .585-.218.65-.534l.402-2.01c.027-.13-.067-.228-.2-.228z"/></svg>
                            @endif
                        </div>
                        <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                            <h2 class="leading-relaxed">Simulated {{ $gateway }} Gateway</h2>
                            <p class="text-sm text-gray-400 font-normal leading-relaxed">Secure Payment Redirection</p>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <div class="flex justify-between bg-gray-50 p-4 rounded-lg">
                                <span class="font-medium">Amount to Pay:</span>
                                <span class="font-bold text-indigo-600">${{ number_format($order->total_cash, 2) }}</span>
                            </div>
                            
                            @if($isSandbox)
                                <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-2 rounded-md text-sm flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    Test/Sandbox Mode Enabled
                                </div>
                            @endif

                            <p class="text-sm text-gray-500 italic mt-4 text-center">
                                This is a simulation page for the {{ $gateway }} checkout experience. In a production environment, you would be seeing the official {{ $gateway }} payment interface.
                            </p>
                        </div>
                        
                        <div class="pt-6 text-base leading-6 font-bold sm:text-lg sm:leading-7">
                            <div class="flex flex-col gap-4">
                                <a href="{{ $success_url }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition transform active:scale-95">
                                    Simulate Success (Pay Now)
                                </a>
                                <a href="{{ $cancel_url }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition transform active:scale-95">
                                    Simulate Cancellation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
