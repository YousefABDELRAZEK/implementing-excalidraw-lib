<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Excalidraw Laravel App</title>

  <!-- React and ReactDOM -->
  <script src="https://unpkg.com/react@18.2.0/umd/react.development.js"></script>
  <script src="https://unpkg.com/react-dom@18.2.0/umd/react-dom.development.js"></script>

  <!-- Excalidraw CDN -->
  <script src="https://unpkg.com/@excalidraw/excalidraw/dist/excalidraw.development.js"></script>

  <style>
    #app {
      width: 100%;
      height: 500px;
      border: 1px solid #ccc;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      padding: 20px;
    }
    button {
      padding: 10px 20px;
      margin: 10px;
      border: none;
      background-color: #007bff;
      color: white;
      cursor: pointer;
      border-radius: 5px;
    }
    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Excalidraw Laravel Integration</h1>
    <button id="save-drawing">Save Drawing</button>
    <button id="load-drawing">Load Drawing</button>
    <div id="app"></div>
  </div>

  <script>
    let excalidrawData = {
      elements: [],
      appState: {},
    };

    // Initialize Excalidraw with initial data
    const root = ReactDOM.createRoot(document.getElementById("app"));
    root.render(
      React.createElement(ExcalidrawLib.Excalidraw, {
        onChange: (elements, appState) => {
          excalidrawData = { elements, appState }; // Update global state on change
        },
        initialData: excalidrawData, // Pass initial data when rendering
      })
    );

    // Fetch default drawing on page load
    document.addEventListener("DOMContentLoaded", () => {
      fetch("http://localhost:8000/default-drawing", {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      })
        .then(async (response) => {
          if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Error: ${response.status} ${errorText}`);
          }
          return response.json();
        })
        .then((data) => {
          const drawing = data.data;
          excalidrawData.elements = JSON.parse(drawing.elements);
          excalidrawData.appState = JSON.parse(drawing.appState);

          // Re-render Excalidraw with updated data
          root.render(
            React.createElement(ExcalidrawLib.Excalidraw, {
              onChange: (elements, appState) => {
                excalidrawData = { elements, appState }; // Update state on change
              },
              initialData: excalidrawData,
            })
          );

          console.log("Default drawing loaded!");
        })
        .catch((error) => {
          console.error("Error fetching default drawing:", error.message);
        });
    });

    // Save Drawing to Backend
    document.getElementById("save-drawing").addEventListener("click", () => {
      if (!excalidrawData.elements.length) {
        alert("No data to save yet!");
        return;
      }

      const data = {
        name: "Test Drawing",
        elements: JSON.stringify(excalidrawData.elements),
        appState: JSON.stringify(excalidrawData.appState),
      };

      fetch("http://localhost:8000/drawings", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(data),
      })
        .then(async (response) => {
          if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Error: ${response.status} ${errorText}`);
          }
          return response.json();
        })
        .then((data) => {
          alert("Drawing saved successfully!");
          console.log("Saved Data:", data);
        })
        .catch((error) => {
          console.error("Error:", error.message);
        });
    });
  </script>

</body>
</html>
