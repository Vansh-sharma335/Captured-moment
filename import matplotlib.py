import matplotlib.pyplot as plt
import pandas as pd

# Define the dataset provided by the user
data = {
    "Question 1": ["Very Effective", "Moderately Effective", "Slightly Effective", "Not Effective", "Very Effective"],
    "Question 2": ["Data quality issues", "False positives", "Model training time", "Integration with current systems", "Cost"],
    "Question 3": ["Very Reliable", "Moderately Reliable", "Slightly Reliable", "Not Reliable", "Very Reliable"],
    "Question 4": ["Highly Valuable", "Somewhat Valuable", "Not Very Valuable", "Not Valuable", "Highly Valuable"],
    "Question 5": ["Better data accuracy", "Improved sensor networks", "More training data", "Energy-efficient models", "Better data accuracy"],
    "Question 6": [5, 4, 3, 5, 4]
}

# Convert to DataFrame
df = pd.DataFrame(data)

# Analysis 1: Question 1 (Bar Graph)
q1_counts = df["Question 1"].value_counts()
plt.figure(figsize=(7, 5))
q1_counts.plot(kind='bar', color='skyblue')
plt.title("Effectiveness of AI in Earthquake Prediction (Question 1)")
plt.xlabel("Responses")
plt.ylabel("Frequency")
plt.xticks(rotation=45)
plt.tight_layout()
plt.savefig("/mnt/data/question_1_analysis_bar.png")

# Analysis 2: Question 2 (Pie Chart)
q2_counts = df["Question 2"].value_counts()
plt.figure(figsize=(7, 5))
q2_counts.plot(kind='pie', autopct='%1.1f%%', colors=["#ff9999","#66b3ff","#99ff99","#ffcc99", "#c2c2f0"], startangle=90)
plt.title("Challenges in AI-based Earthquake Prediction (Question 2)")
plt.ylabel("")
plt.savefig("/mnt/data/question_2_analysis_pie.png")

# Analysis 3: Question 3 (Bar Graph)
q3_counts = df["Question 3"].value_counts()
plt.figure(figsize=(7, 5))
q3_counts.plot(kind='bar', color='lightgreen')
plt.title("Reliability of Deep Learning in Reducing False Positives (Question 3)")
plt.xlabel("Responses")
plt.ylabel("Frequency")
plt.xticks(rotation=45)
plt.tight_layout()
plt.savefig("/mnt/data/question_3_analysis_bar.png")

# Analysis 4: Question 4 (Pie Chart)
q4_counts = df["Question 4"].value_counts()
plt.figure(figsize=(7, 5))
q4_counts.plot(kind='pie', autopct='%1.1f%%', colors=["#ff9999","#66b3ff","#99ff99","#ffcc99"], startangle=90)
plt.title("Integration of AI in Disaster Preparedness (Question 4)")
plt.ylabel("")
plt.savefig("/mnt/data/question_4_analysis_pie.png")

# Analysis 5: Question 5 (Bar Graph)
q5_counts = df["Question 5"].value_counts()
plt.figure(figsize=(7, 5))
q5_counts.plot(kind='bar', color='orange')
plt.title("Recommended Improvements for AI in Seismic Analysis (Question 5)")
plt.xlabel("Responses")
plt.ylabel("Frequency")
plt.xticks(rotation=45)
plt.tight_layout()
plt.savefig("/mnt/data/question_5_analysis_bar.png")

# Analysis 6: Question 6 (Bar Graph)
q6_counts = df["Question 6"].value_counts()
plt.figure(figsize=(7, 5))
q6_counts.plot(kind='bar', color='purple')
plt.title("Importance of AI in Seismic Monitoring Systems (Question 6)")
plt.xlabel("Rating (1-5)")
plt.ylabel("Frequency")
plt.xticks(rotation=0)
plt.tight_layout()
plt.savefig("/mnt/data/question_6_analysis_bar.png")

# Return file paths for download
file_paths = [
    "/mnt/data/question_1_analysis_bar.png",
    "/mnt/data/question_2_analysis_pie.png",
    "/mnt/data/question_3_analysis_bar.png",
    "/mnt/data/question_4_analysis_pie.png",
    "/mnt/data/question_5_analysis_bar.png",
    "/mnt/data/question_6_analysis_bar.png"
]

file_paths
