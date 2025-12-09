<x-admin-layout>
    <div class="max-w-7xl mx-auto">

        <!-- Filter & Print -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex flex-col md:flex-row gap-2 items-start md:items-center">
                <!-- Filter range -->
                <form method="GET" class="flex gap-2 items-center">
                    <label for="range" class="font-semibold text-gray-700 dark:text-gray-200">Filter:</label>
                    <select name="range" onchange="this.form.submit()" class="border rounded p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <option value="all" {{ $range=='all' ? 'selected' : '' }}>All</option>
                        <option value="daily" {{ $range=='daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $range=='weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $range=='monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </form>

                <!-- Print Report -->
                <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition mt-2 md:mt-0">
                    Print Report
                </button>
            </div>
        </div>

        <!-- Booking List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bookings as $booking)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition duration-300">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $booking->service }}</h3>
                    @if($booking->sub_service != '-')
                    <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-1 rounded-full">{{ $booking->sub_service }}</span>
                    @endif
                </div>
                <div class="space-y-1 text-gray-700 dark:text-gray-300">
                    <p><strong>Store:</strong> {{ $booking->store }}</p>
                    <p><strong>Barber:</strong> {{ $booking->barber }}</p>
                    <p><strong>Gender:</strong> {{ $booking->gender }}</p>
                    <p>
                        <strong>Date & Time:</strong> 
                        {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->time)->format('H:i') }}
                    </p>
                </div>
                <p class="text-orange-600 font-bold text-lg mt-4">Rp {{ number_format($booking->price, 0, ',', '.') }}</p>
            </div>
            @empty
            <p class="text-gray-700 dark:text-gray-200 col-span-3">No bookings found for selected range.</p>
            @endforelse
        </div>

    </div>

    <!-- Print Styling -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .max-w-7xl,
            .max-w-7xl * {
                visibility: visible;
            }

            .max-w-7xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button,
            form {
                display: none;
            }
        }
    </style>

</x-admin-layout>
