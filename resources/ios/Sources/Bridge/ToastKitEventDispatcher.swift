import Foundation

enum ToastKitEventDispatcher {
    private static let shownEvent = "Victorycodedev\\ToastKit\\Events\\ToastShown"
    private static let dismissedEvent = "Victorycodedev\\ToastKit\\Events\\ToastDismissed"
    private static let actionEvent = "Victorycodedev\\ToastKit\\Events\\ToastActionPressed"
    private static let pressedEvent = "Victorycodedev\\ToastKit\\Events\\ToastPressed"

    static func shown(_ id: String) { send(shownEvent, ["toastId": id]) }
    static func dismissed(_ id: String, reason: String) { send(dismissedEvent, ["toastId": id, "reason": reason]) }
    static func action(_ id: String, actionId: String, data: [String: Any]) { send(actionEvent, ["toastId": id, "actionId": actionId, "data": data]) }
    static func pressed(_ id: String, data: [String: Any]) { send(pressedEvent, ["toastId": id, "data": data]) }

    private static func send(_ event: String, _ payload: [String: Any?]) {
        LaravelBridge.shared.send?(event, payload)
    }
}
