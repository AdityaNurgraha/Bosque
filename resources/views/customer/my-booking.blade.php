<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Booking - Bosque Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-sm mb-6">
        <div class="max-w-5xl mx-auto flex justify-between items-center py-6 px-4">
            <div>
                <h1 class="text-3xl font-bold">My Booking</h1>
                <p class="text-gray-600">Hi, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('book.create') }}" class="text-orange-500 font-semibold hover:text-orange-600">
                    + New Booking
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Flash Message -->
    @if (session('success'))
    <div class="max-w-5xl mx-auto mb-6 px-4">
        <div class="p-4 bg-green-100 border border-green-300 rounded text-green-800">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 space-y-6 pb-10">

        @if($bookings->isEmpty())
        <p class="text-gray-500 text-center py-10">You don’t have any bookings yet.</p>
        @endif

        @php $tz = 'Asia/Jakarta'; @endphp

        @foreach ($bookings as $booking)

        @php
        try { $date = \Carbon\Carbon::parse($booking->date)->format('Y-m-d'); } catch (\Exception $e) { $date = $booking->date; }
        try { $time = \Carbon\Carbon::parse($booking->time)->format('H:i'); } catch (\Exception $e) { $time = substr($booking->time,0,5); }

        $bookingDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date." ".$time, $tz);
        $now = \Carbon\Carbon::now($tz);

        $status = $bookingDateTime->lte($now) ? 'Completed' : 'Upcoming';
        $statusColor = $status === 'Completed' ? 'bg-gray-500' : 'bg-green-500';
        @endphp

        <!-- RECEIPT CARD -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">

            <!-- Top -->
            <div class="flex justify-between items-center p-5 border-b border-dashed border-gray-300 bg-gray-50">
                <div>
                    <h2 class="text-xl font-bold">Booking Receipt</h2>
                    <p class="text-sm text-gray-500">Bosque Barbershop</p>
                </div>

                <span class="px-3 py-1 rounded text-white text-sm {{ $statusColor }}">
                    {{ $status }}
                </span>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-sm">
                <div>
                    <p class="text-gray-500">Store</p>
                    <p class="font-semibold capitalize">{{ $booking->store }}</p>

                    <p class="text-gray-500 mt-3">Gender</p>
                    <p class="font-semibold capitalize">{{ $booking->gender }}</p>

                    <p class="text-gray-500 mt-3">Service</p>
                    <p class="font-semibold capitalize">{{ $booking->service }}</p>

                    @if ($booking->sub_service)
                    <p class="text-gray-500 mt-3">Sub Service</p>
                    <p class="font-semibold capitalize">{{ $booking->sub_service }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-gray-500">Barber</p>
                    <p class="font-semibold capitalize">{{ $booking->barber }}</p>

                    <p class="text-gray-500 mt-3">Date</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</p>

                    <p class="text-gray-500 mt-3">Time</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->time)->format('H:i') }}</p>

                    <p class="text-gray-500 mt-3">Total Price</p>
                    <p class="font-semibold text-orange-600">
                        Rp {{ number_format($booking->price ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Reschedule Button -->
            <div class="p-4 border-t bg-white flex justify-center">
                @if($status === 'Upcoming')
                <button onclick="openNotif('{{ $booking->id }}')" class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                    Reschedule
                </button>
                @endif
            </div>

            <!-- Footer receipt -->
            <div class="bg-gray-50 text-xs text-gray-500 border-t border-dashed border-gray-300 p-4 text-center">
                Booking ID: <span class="font-semibold">{{ $booking->id }}</span>
                <br>
                <span class="text-gray-400">Server time ({{ $tz }}):
                    {{ \Carbon\Carbon::now($tz)->format('d M Y H:i') }}
                </span>
            </div>
        </div>

        @endforeach
    </div>

    <!-- POPUP NOTIF -->
    <div id="notifPopup"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center px-4">
        <div class="bg-white rounded-xl shadow-xl p-6 text-center max-w-sm w-full">
            <h3 class="text-xl font-bold mb-2">Reschedule Booking</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
                Untuk perubahan jadwal booking, silakan hubungi admin Bosque Barbershop:
            </p>

            <p class="text-lg font-semibold text-orange-600 mt-3">
                📞 0812-3456-7890
            </p>

            <button onclick="closeNotif()"
                class="mt-5 bg-gray-700 text-white w-full py-2 rounded-lg hover:bg-gray-800 transition">
                Oke, Mengerti
            </button>
        </div>
    </div>

    <script>
        function openNotif(id) {
            document.getElementById("notifPopup").classList.remove("hidden");
        }

        function closeNotif() {
            document.getElementById("notifPopup").classList.add("hidden");
        }
    </script>

    <footer class="text-center text-gray-500 py-6">
        &copy; {{ date('Y') }} Bosque Barbershop. All rights reserved.
    </footer>

</body>

</html>