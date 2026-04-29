# ride_sharing_user_app

A ride sharing Flutter project.

## Getting Started

This project is a starting point for a Flutter application.

A few resources to get you started if this is your first Flutter project:

- [Lab: Write your first Flutter app](https://docs.flutter.dev/get-started/codelab)
- [Cookbook: Useful Flutter samples](https://docs.flutter.dev/cookbook)

For help getting started with Flutter development, view the
[online documentation](https://docs.flutter.dev/), which offers tutorials,
samples, guidance on mobile development, and a full API reference.

## Local API setup (base URL)

This app needs an API base URL at runtime. Set it with `--dart-define`.

Use the correct host for your target device:
- Android emulator (AVD): `http://10.0.2.2:8000`
- Android Genymotion: `http://10.0.3.2:8000`
- iOS simulator: `http://127.0.0.1:8000`
- Physical device (same Wi-Fi as your PC): `http://YOUR_PC_LAN_IP:8000`

Run (replace the URL for your target):

```powershell
flutter run --dart-define=BASE_URL=http://10.0.2.2:8000
```

Optional: pass your Google Maps key (if required):

```powershell
flutter run --dart-define=BASE_URL=http://10.0.2.2:8000 --dart-define=MAPS_API_KEY=YOUR_MAPS_KEY
```
