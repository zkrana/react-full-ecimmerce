// Your existing Chart.js setup
var ctx = document.getElementById("salesChart").getContext("2d");
var salesChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: labels,
    datasets: [
      {
        label: "Total Sales",
        data: salesData,
        backgroundColor: "rgba(75, 192, 192, 0.5)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
    ],
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: "Total Sales ($)",
        },
      },
      x: {
        title: {
          display: true,
          text: "Order Date",
        },
      },
    },
  },
});

// Function to update the chart with new data
function updateChart(labels, salesData) {
  salesChart.data.labels = labels;
  salesChart.data.datasets[0].data = salesData;
  salesChart.update();
}

// Functions to switch between different charts
function showDailyChart() {
  // Fetch daily data from API
  fetchDailyData()
    .then((data) => {
      const dailyLabels = data.map((entry) => entry.date);
      const dailySalesData = data.map((entry) => parseFloat(entry.total_sales));
      updateChart(dailyLabels, dailySalesData);
    })
    .catch((error) => {
      console.error("Error fetching daily data:", error);
    });
}

// Example function to fetch daily data from an API endpoint
function fetchDailyData() {
  return fetch("./chart-data/daily-data.php").then((response) => {
    if (!response.ok) {
      throw new Error("Failed to fetch daily data");
    }
    return response.json();
  });
}

// Function to switch to weekly chart and fetch data
function showWeeklyChart() {
  // Fetch weekly data from API
  fetchWeeklyData()
    .then((data) => {
      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      const weeklyLabels = data.map((entry) => {
        const startDate = new Date(entry.year, entry.month - 1, 1); // Start date of the month
        const startWeek = Math.ceil((entry.week - 1) / 5); // Calculate the week number within the month
        const endDate = new Date(entry.year, entry.month - 1, 7 * startWeek); // End date of the week
        const month = months[entry.month - 1];
        return (
          entry.year +
          " - " +
          month +
          " - Week " +
          startWeek +
          " (" +
          startDate.toDateString() +
          " to " +
          endDate.toDateString() +
          ")"
        );
      });
      const weeklySalesData = data.map((entry) =>
        parseFloat(entry.total_sales)
      );
      updateChart(weeklyLabels, weeklySalesData);
    })
    .catch((error) => {
      console.error("Error fetching weekly data:", error);
    });
}

// Example function to fetch weekly data from an API endpoint
function fetchWeeklyData() {
  return fetch("./chart-data/weekly-data.php").then((response) => {
    if (!response.ok) {
      throw new Error("Failed to fetch weekly data");
    }
    return response.json();
  });
}

// Function to switch to monthly chart and fetch data
function showMonthlyChart() {
  // Fetch monthly data from API
  fetchMonthlyData()
    .then((data) => {
      const monthlyLabels = data.map(
        (entry) => entry.year + " - " + getMonthName(entry.month)
      );
      const monthlySalesData = data.map((entry) =>
        parseFloat(entry.total_sales)
      );
      updateChart(monthlyLabels, monthlySalesData);
    })
    .catch((error) => {
      console.error("Error fetching monthly data:", error);
    });
}

// Example function to fetch monthly data from an API endpoint
function fetchMonthlyData() {
  return fetch("./chart-data/monthly-data.php").then((response) => {
    if (!response.ok) {
      throw new Error("Failed to fetch monthly data");
    }
    return response.json();
  });
}

// Helper function to get month name from month number
function getMonthName(month) {
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];
  return months[month - 1];
}

// Function to switch to yearly chart and fetch data
function showYearlyChart() {
  // Fetch yearly data from API
  fetchYearlyData()
    .then((data) => {
      const yearlyLabels = data.map((entry) => entry.year);
      const yearlySalesData = data.map((entry) =>
        parseFloat(entry.total_sales)
      );
      updateChart(yearlyLabels, yearlySalesData);
    })
    .catch((error) => {
      console.error("Error fetching yearly data:", error);
    });
}

// Example function to fetch yearly data from an API endpoint
function fetchYearlyData() {
  return fetch("./chart-data/yearly-data.php").then((response) => {
    if (!response.ok) {
      throw new Error("Failed to fetch yearly data");
    }
    return response.json();
  });
}
