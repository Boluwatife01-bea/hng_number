# 📊 Number Classification API

A simple Laravel API that classifies numbers based on their mathematical properties and provides a fun fact about the number.

---

## 🚀 Features
- **Prime Check** – Determines if a number is prime.
- **Perfect Number Check** – Checks if a number is perfect.
- **Armstrong Number Check** – Identifies Armstrong numbers.
- **Parity Check** – Determines if a number is even or odd.
- **Digit Sum Calculation** – Computes the sum of digits of the number.
- **Fun Fact Retrieval** – Fetches a fun fact from the [Numbers API](http://numbersapi.com).

---

🔗 **GitHub Repository:** [NumberClassificationAPI]((https://github.com/Boluwatife01-bea/hng_task1))

---

## 📡 API Endpoints
### **GET /api/classify-number**
#### **Query Parameters**
| Parameter | Type  | Required | Description |
|-----------|-------|----------|-------------|
| `number`  | `int` | ✅ Yes   | The number to classify (negative numbers are converted to positive) |

#### ✅ **Example Response**
```json
{
    "number": 371,
    "is_prime": false,
    "is_perfect": false,
    "properties": ["armstrong", "odd"],
    "digit_sum": 11,
    "fun_fact": "371 is an Armstrong number because 3^3 + 7^3 + 1^3 = 371"
}

